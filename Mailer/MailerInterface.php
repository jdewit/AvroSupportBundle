<?php

namespace Avro\SupportBundle\Mailer;

use Avro\SupportBundle\Event\QuestionEvent;
use Avro\SupportBundle\Event\AnswerEvent;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
interface MailerInterface
{

    public function sendQuestionCreatedEmail(QuestionEvent $event);

    public function sendAnswerCreatedEmail(AnswerEvent $event);

}
