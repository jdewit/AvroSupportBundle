<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Question controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionController extends ContainerAware
{
    /**
     * List all questions
     */
    public function listAction(Request $request, $filter)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $paginator = $this->container->get('knp_paginator');

        $query = $this->container->get('avro_support.question.manager')->getUsersQuestionsQuery($user->getId(), $filter);

        $questions = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:list.html.twig', array(
            'questions' => $questions,
            'filter' => $filter,
            'minRole' => $this->container->getParameter('avro_support.min_role'),
            'adminRole' => $this->container->getParameter('avro_support.admin_role')
        ));
    }

    /**
     * Create a question
     */
    public function newAction(Request $request)
    {
        $form = $this->container->get('avro_support.question.form');
        $questionManager = $this->container->get('avro_support.question.manager');

        $question = $questionManager->create();

        $form->setData($question);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $questionManager->persist($question);
                $translator = $this->container->get('translator');

                $request->getSession()->getFlashBag()->set('success', $translator->trans('avro_support.question.created.flash', array(), 'AvroSupportBundle'));

                return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId())));
            }
        }

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:new.html.twig', array(
            'form' => $form->createView(),
            'minRole' => $this->container->getParameter('avro_support.min_role'),
            'adminRole' => $this->container->getParameter('avro_support.admin_role'),
            'isNew' => true
        ));
    }

    /**
     * Show a question
     */
    public function showAction(Request $request, $id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');
        $context = $this->container->get('security.context');
        $user = $context->getToken()->getUser();

        $question = $questionManager->find($id);

        // increment views
        if ($user->getId() !== $question->getAuthorId()) {
            $question->incrementViews();
            $questionManager->update($question);
        }

        $form = $this->container->get('avro_support.answer.form');

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:show.html.twig', array(
            'question' => $question,
            'form' => $form->createView(),
            'allow_anon' => $this->container->getParameter('avro_support.question.allow_anon'),
            'adminRole' => $this->container->getParameter('avro_support.admin_role'),
            'minRole' => $this->container->getParameter('avro_support.min_role'),
            'admin' => $request->query->get('admin') === 'true' ? true : false
        ));
    }

    /**
     * Stop notifications on a question
     */
    public function stopNotificationsAction(Request $request, $id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');
        $translator = $this->container->get('translator');

        $question = $questionManager->find($id);
        $question->setSendNotification(false);

        $questionManager->update($question);

        $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.question.updated.flash', array(), 'AvroSupportBundle'));

        $referer = $request->headers->get('referer');

        if (!$referer) {
            $referer = $this->container->get('router')->generate('avro_support_question_show', array('id' => $id));
        }

        return new RedirectResponse($referer);
    }

    /**
     * Edit a question
     */
    public function editAction(Request $request, $id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');
        $context = $this->container->get('security.context');
        $user = $context->getToken()->getUser();
        $adminRole = $this->container->getParameter('avro_support.admin_role');

        $question = $questionManager->find($id);

        if (!$context->isGranted($adminRole) && ($question->getIsPublic() || $question->getIsFaq())) {
            $this->container->get('session')->getFlashBag()->set('danger', $translator->trans('avro_support.accessDenied.flash', array(), 'AvroSupportBundle'));
        } else {
            $form = $this->container->get('avro_support.question.form');

            $form->setData($question);

            if ('POST' === $request->getMethod()) {
                $form->bind($request);
                if ($form->isValid()) {
                    $questionManager->update($question);
                    $translator = $this->container->get('translator');

                    $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.question.updated.flash', array(), 'AvroSupportBundle'));

                    $referer = $request->headers->get('referer');
                    $destination = $this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId()));

                    if ($referer) {
                        if (strpos($referer, 'admin') !== false) {
                            $destination = $this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId(), 'admin' => true));
                        }
                    }

                    return new RedirectResponse($destination);
                }
            }
        }

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:edit.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
            'minRole' => $this->container->getParameter('avro_support.min_role'),
            'adminRole' => $adminRole,
            'admin' => $request->query->get('admin') === 'true' ? true : false,
            'backToQuestion' => $request->query->get('backToQuestion') === 'true' ? true : false,
            'userId' => $user->getId()
        ));
    }

    /**
     * Close a question
     */
    public function closeAction(Request $request, $id)
    {
        $context = $this->container->get('security.context');
        $adminRole = $this->container->getParameter('avro_support.admin_role');
        $question = $this->container->get('avro_support.question.manager')->find($id);
        $translator = $this->container->get('translator');

        if (!$context->isGranted($adminRole) && ($question->getIsPublic() || $question->getIsFaq())) {
            $this->container->get('session')->getFlashBag()->set('danger', $translator->trans('avro_support.accessDenied.flash', array(), 'AvroSupportBundle'));
        } else {
            $question->setIsSolved(true);
            $question->setSolvedAt(new \Datetime('now'));

            $this->container->get('avro_support.question.manager')->update($question);

            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.question.solved.flash', array(), 'AvroSupportBundle'));
        }

        $referer = $request->headers->get('referer');
        $destination = $this->container->get('router')->generate('avro_support_question_list');

        if ($referer) {
            if (strpos($referer, 'admin') !== false) {
                $destination = $this->container->get('router')->generate('avro_support_admin_list');
            }
        }

        return new RedirectResponse($destination);
    }

    /**
     * Reopen a question
     */
    public function openAction(Request $request, $id)
    {
        $context = $this->container->get('security.context');
        $adminRole = $this->container->getParameter('avro_support.admin_role');
        $question = $this->container->get('avro_support.question.manager')->find($id);
        $translator = $this->container->get('translator');

        if (!$context->isGranted($adminRole) && ($question->getIsPublic() || $question->getIsFaq())) {
            $this->container->get('session')->getFlashBag()->set('danger', $translator->trans('avro_support.accessDenied.flash', array(), 'AvroSupportBundle'));
        } else {
            $question->setIsSolved(false);

            $this->container->get('avro_support.question.manager')->update($question);

            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.question.reopened.flash', array(), 'AvroSupportBundle'));
        }

        $referer = $request->headers->get('referer');
        $destination = $this->container->get('router')->generate('avro_support_question_list');

        if ($referer) {
            if (strpos($referer, 'admin') !== false) {
                $destination = $this->container->get('router')->generate('avro_support_admin_list');
            }
        }

        return new RedirectResponse($destination);
    }

    /**
     * Delete a question
     */
    public function deleteAction(Request $request, $id)
    {
        $context = $this->container->get('security.context');
        $adminRole = $this->container->getParameter('avro_support.admin_role');
        $question = $this->container->get('avro_support.question.manager')->find($id);
        $translator = $this->container->get('translator');

        if (!$context->isGranted($adminRole) && ($question->getIsPublic() || $question->getIsFaq())) {
            $this->container->get('session')->getFlashBag()->set('danger', $translator->trans('avro_support.accessDenied.flash', array(), 'AvroSupportBundle'));
        } else {
            $this->container->get('avro_support.question.manager')->delete($question);

            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.question.deleted.flash', array(), 'AvroSupportBundle'));
        }

        $referer = $request->headers->get('referer');
        $destination = $this->container->get('router')->generate('avro_support_question_list');

        if ($referer) {
            if (strpos($referer, 'admin') !== false) {
                $destination = $this->container->get('router')->generate('avro_support_admin_list');
            }
        }

        return new RedirectResponse($destination);
    }

    /**
     * -- INCOMPLETE AND UNUSED --
     */
    public function restoreAction(Request $request, $id)
    {
        if ($id) {
            $question = $this->container->get('avro_support.question.manager')->find($id);
            $translator = $this->container->get('translator');

            $this->container->get('avro_support.question.manager')->restore($question);

            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.question.restored.flash', array(), 'AvroSupportBundle'));
        }

        $referer = $request->headers->get('referer');

        if (!$referer) {
            $referer = $this->container->get('router')->generate('avro_support_question_list');
        }

        return new RedirectResponse($referer);
    }

    /**
     * Add an answer
     */
    public function addAnswerAction(Request $request, $id)
    {
        $context = $this->container->get('security.context');
        $adminRole = $this->container->getParameter('avro_support.admin_role');
        $questionManager = $this->container->get('avro_support.question.manager');
        $form = $this->container->get('avro_support.answer.form');

        if (!$context->isGranted($adminRole)) {
            $this->container->get('session')->getFlashBag()->set('danger', $translator->trans('avro_support.accessDenied.flash', array(), 'AvroSupportBundle'));
        } else {
            $question = $questionManager->find($id);
            $answer = $questionManager->createAnswer();

            $form->setData($answer);

            if ('POST' === $request->getMethod()) {
                $form->bind($request);
                $translator = $this->container->get('translator');

                if ($form->isValid()) {
                    $answer = $form->getData();
                    $questionManager->addAnswer($question, $answer);

                    $request->getSession()->getFlashBag()->set('success', $translator->trans('avro_support.answer.added.flash', array(), 'AvroSupportBundle'));
                } else {
                    $request->getSession()->getFlashBag()->set('danger', $translator->trans('avro_support.answer.add_failed.flash', array(), 'AvroSupportBundle'));
                }
            }
        }

        $referer = $request->headers->get('referer');
        $destination = $this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId()));

        if ($referer) {
            if (strpos($referer, 'admin') !== false) {
                $destination = $this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId(), 'admin' => true));
            }
        }

        return new RedirectResponse($destination);
    }

    /**
     * Delete an answer
     */
    public function deleteAnswerAction(Request $request, $questionId, $answerId)
    {
        $context = $this->container->get('security.context');
        $adminRole = $this->container->getParameter('avro_support.admin_role');
        $questionManager = $this->container->get('avro_support.question.manager');
        $translator = $this->container->get('translator');

        if (!$context->isGranted($adminRole)) {
            $this->container->get('session')->getFlashBag()->set('danger', $translator->trans('avro_support.accessDenied.flash', array(), 'AvroSupportBundle'));
        } else {
            $question = $questionManager->find($questionId);

            $questionManager->removeAnswer($question, $answerId);

            $questionManager->update($question);

            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.answer.deleted.flash', array(), 'AvroSupportBundle'));
        }

        $referer = $request->headers->get('referer');
        $destination = $this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId()));

        if ($referer) {
            if (strpos($referer, 'admin') !== false) {
                $destination = $this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId(), 'admin' => true));
            }
        }

        return new RedirectResponse($destination);
    }


}
