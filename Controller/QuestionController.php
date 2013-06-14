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
    public function listAction($filter)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $paginator = $this->container->get('avro_support.question_manager')->getUsersQuestions($user->getId(), $filter);

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:list.html.twig', array(
            'paginator' => $paginator,
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

                $request->getSession()->getFlashBag()->set('success', 'question.created.flash');

                return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('slug' => $question->getId())));
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
        $question = $this->container->get('avro_support.question_manager')->show($slug);

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
        $questionManager = $this->container->get('avro_support.question_manager');

        $question = $questionManager->find($id);
        $question->setSendNotification(false);

        $questionManager->update($question);

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())));
    }

    /**
     * Edit a question
     */
    public function editAction($slug)
    {
        $question = $this->container->get('avro_support.question_manager')->findBySlug($slug);

        if (!$question) {
            $this->container->get('session')->getFlashBag()->set('notice', ' Question not found.');

            return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'));
        }

        $form = $this->container->get('avro_support.question.form');
        $formHandler = $this->container->get('avro_support.question.form.handler');

        $process = $formHandler->process($question);
        if ($process) {
            $question = $form->getData('question');
            $this->container->get('session')->getFlashBag()->set('success', 'question.updated.flash');

            return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'));
        }

        return array(
            'form' => $form->createView(),
            'question' => $question,
        );
    }

    /**
     * Close a question
     */
    public function closeAction($id)
    {
        $question = $this->container->get('avro_support.question_manager')->find($id);

        $question->setIsSolved(true);
        $question->setSolvedAt(new \Datetime('now'));

        $this->container->get('avro_support.question_manager')->update($question);

        $this->container->get('session')->getFlashBag()->set('success', 'question.solved.flash');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'), 301);
    }

    /**
     * Delete a question
     */
    public function deleteAction($id)
    {
        $question = $this->container->get('avro_support.question_manager')->find($id);
        $this->container->get('avro_support.question_manager')->delete($question);

        $this->container->get('session')->getFlashBag()->set('success', 'Question deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'), 301);
    }

    /**
     */
    public function restoreAction($id)
    {
        if ($id) {
            $question = $this->container->get('avro_support.question_manager')->find($id);
            $this->container->get('avro_support.question_manager')->restore($question);

            $this->container->get('session')->getFlashBag()->set('success', ' Question restored.');
        }

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_list'), 301);
    }

    /**
     * Add an answer
     */
    public function addAnswerAction($id)
    {
        $question = $this->container->get('avro_support.question_manager')->find($id);

        $form = $this->container->get('avro_support.answer.form');
        $formHandler = $this->container->get('avro_support.answer.form.handler');

        $process = $formHandler->process($question);

        if ($process) {
            $answer = $form->getData();
            $this->container->get('session')->getFlashBag()->set('success', 'Answer added.');

            return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())), 301);
        }
    }

    /**
     * Delete an answer
     */
    public function deleteAnswerAction($questionId, $answerId)
    {
        $questionManager = $this->container->get('avro_support.question_manager');

        $question = $questionManager->find($questionId);

        $questionManager->removeAnswer($question, $answerId);

        $questionManager->update($question);

        $this->container->get('session')->getFlashBag()->set('success', 'Answer deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())), 301);
    }


}
