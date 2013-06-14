<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Question controller.
 *
 * @Route("/question")
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionController extends controller
{
    /**
     * @Template()
     */
    public function listAction($filter)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $paginator = $this->container->get('avro_support.question_manager')->getUsersQuestions($user->getId(), $filter);

        return array(
            'paginator' => $paginator,
            'filter' => $filter
        );
    }

    /**
     * @Template()
     */
    public function newAction()
    {
        $form = $this->container->get('avro_support.question.form');
        $formHandler = $this->container->get('avro_support.question.form.handler');
        $process = $formHandler->process();
        if ($process) {
            $this->get('session')->getFlashBag()->set('success', 'Question created.');

            $question = $form->getData();

            return new RedirectResponse($this->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())));
        }

        $isUser = $this->get('security.context')->isGranted($this->container->getParameter('avro_support.min_role'));

        return array(
            'form' => $form->createView(),
            'isUser' => $isUser
        );
    }

    /**
     * @Template
     */
    public function showAction($slug)
    {
        $question = $this->container->get('avro_support.question_manager')->show($slug);

        $form = $this->container->get('avro_support.answer.form');

        return array(
            'question' => $question,
            'form' => $form->createView(),
            'allow_anon' => $this->container->getParameter('avro_support.question.allow_anon')
        );
    }

    /**
     */
    public function stopNotificationsAction($id)
    {
        $questionManager = $this->get('avro_support.question_manager');

        $question = $questionManager->find($id);
        $question->setSendNotification(false);

        $questionManager->update($question);

        return new RedirectResponse($this->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())));
    }

    /**
     * @Template
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
            $this->container->get('session')->getFlashBag()->set('success', 'Question updated.');

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
        $this->container->get('avro_support.question_manager')->close($question);

        $this->container->get('session')->getFlashBag()->set('success', 'Question has been marked as solved.');

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
        $question = $this->get('avro_support.question_manager')->find($id);

        $form = $this->container->get('avro_support.answer.form');
        $formHandler = $this->container->get('avro_support.answer.form.handler');

        $process = $formHandler->process($question);

        if ($process) {
            $answer = $form->getData();
            $this->container->get('session')->getFlashBag()->set('success', 'Answer added.');

            return new RedirectResponse($this->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())), 301);
        }
    }

    /**
     * Delete an answer
     */
    public function deleteAnswerAction($questionId, $answerId)
    {
        $questionManager = $this->get('avro_support.question_manager');

        $question = $questionManager->find($questionId);

        $questionManager->removeAnswer($question, $answerId);

        $questionManager->update($question);

        $this->container->get('session')->getFlashBag()->set('success', 'Answer deleted.');

        return new RedirectResponse($this->get('router')->generate('avro_support_question_show', array('slug' => $question->getSlug())), 301);
    }


}
