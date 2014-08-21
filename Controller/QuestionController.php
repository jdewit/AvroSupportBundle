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
            'filter' => $filter
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

                $request->getSession()->getFlashBag()->set('success', 'avro_support.question.created.flash');

                return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId())));
            }
        }

        $isUser = $this->container->get('security.context')->isGranted($this->container->getParameter('avro_support.min_role'));

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:new.html.twig', array(
            'form' => $form->createView(),
            'isUser' => $isUser
        ));
    }

    /**
     * Show a question
     */
    public function showAction($id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');
        $context = $this->container->get('security.context');
        $user = $context->getToken()->getUser();

        $question = $questionManager->find($id);

        // increment views
        if ($user->getId() !== $question->getAuthorId()) {
            $question->setViews($question->getViews() + 1);
            $questionManager->update($question);
        }

        $form = $this->container->get('avro_support.answer.form');

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:show.html.twig', array(
            'question' => $question,
            'form' => $form->createView(),
            'allow_anon' => $this->container->getParameter('avro_support.question.allow_anon')
        ));
    }

    /**
     * Stop notifications on a question
     */
    public function stopNotificationsAction($id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');

        $question = $questionManager->find($id);
        $question->setSendNotification(false);

        $questionManager->update($question);

        $this->container->get('session')->getFlashBag()->set('success', 'avro_support.question.updated.flash');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('id' => $id)));
    }

    /**
     * Edit a question
     */
    public function editAction(Request $request, $id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');

        $question = $questionManager->find($id);

        $form = $this->container->get('avro_support.question.form');

        $form->setData($question);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $questionManager->update($question);

                $this->container->get('session')->getFlashBag()->set('success', 'avro_support.question.updated.flash');

                return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('id' => $question->getId())));
            }
        }

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:edit.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

    /**
     * Close a question
     */
    public function closeAction($id)
    {
        $question = $this->container->get('avro_support.question.manager')->find($id);

        $question->setIsSolved(true);
        $question->setSolvedAt(new \Datetime('now'));

        $this->container->get('avro_support.question.manager')->update($question);

        $this->container->get('session')->getFlashBag()->set('success', 'avro_support.question.solved.flash');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'));
    }

    /**
     * Delete a question
     */
    public function deleteAction($id)
    {
        $question = $this->container->get('avro_support.question.manager')->find($id);
        $this->container->get('avro_support.question.manager')->delete($question);

        $this->container->get('session')->getFlashBag()->set('success', 'avro.support.question.deleted.flash');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'));
    }

    /**
     */
    public function restoreAction($id)
    {
        if ($id) {
            $question = $this->container->get('avro_support.question.manager')->find($id);
            $this->container->get('avro_support.question.manager')->restore($question);

            $this->container->get('session')->getFlashBag()->set('success', 'avro_support.question.restored.flash');
        }

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'));
    }

    /**
     * Add an answer
     */
    public function addAnswerAction(Request $request, $id)
    {
        $questionManager = $this->container->get('avro_support.question.manager');
        $form = $this->container->get('avro_support.answer.form');

        $question = $questionManager->find($id);
        $answer = $questionManager->createAnswer();

        $form->setData($answer);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $answer = $form->getData();
                $questionManager->addAnswer($question, $answer);

                $request->getSession()->getFlashBag()->set('success', 'avro_support.answer.added.flash');
            } else {
                $request->getSession()->getFlashBag()->set('danger', 'avro_support.answer.added_failed.flash');
            }
        }

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('id' => $id)));
    }

    /**
     * Delete an answer
     */
    public function deleteAnswerAction($questionId, $answerId)
    {
        $questionManager = $this->container->get('avro_support.question.manager');

        $question = $questionManager->find($questionId);

        $questionManager->removeAnswer($question, $answerId);

        $questionManager->update($question);

        $this->container->get('session')->getFlashBag()->set('success', 'avro_support.answer.deleted.flash');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('id' => $id)));
    }


}
