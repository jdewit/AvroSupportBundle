<?php

namespace Avro\SupportBundle\Mailer;

use Avro\SupportBundle\Event\QuestionEvent;
use Avro\SupportBundle\Event\AnswerEvent;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class BaseMailer implements MailerInterface
{

    public function sendQuestionCreatedEmail(QuestionEvent $event)
    {
        $question = $event->getQuestion();

        if ($question->getAuthorEmail()) {
            // send message to user
            $rendered = $this->templating->render('AvroSupportBundle:Email:user/question_created.html.twig', array(
                'question' => $question,
                'email_signature' => $this->parameters['email_signature']
            ));
            $this->sendEmailMessage($rendered, $this->parameters['from_email'], $question->getAuthorEmail());
        }

        // send message to superadmin
        $rendered = $this->templating->render('AvroSupportBundle:Email:admin/question_created.html.twig', array(
            'question' => $question,
            'email_signature' => $this->parameters['email_signature']
        ));

        $this->sendEmailMessage($rendered, $this->parameters['from_email'], $this->parameters['from_email']);
    }

    public function sendAnswerCreatedEmail(AnswerEvent $event)
    {
        $answer = $event->getAnswer();
        $question = $answer->getQuestion();

        $rendered = $this->templating->render('AvroSupportBundle:Email:user/answer_created.html.twig', array(
            'answer' => $answer,
            'question' => $question,
            'email_signature' => $this->parameters['email_signature']
        ));

        $this->sendEmailMessage($rendered, $this->parameters['from_email'], $question->getAuthorEmail());
    }
}
