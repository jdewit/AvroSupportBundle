<?php

namespace Avro\SupportBundle\Mailer;

use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Model\AnswerInterface;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
interface MailerInterface
{

    public function sendQuestionCreatedEmail(QuestionInterface $question);

    public function sendAnswerCreatedEmail(QuestionInterface $question, AnswerInterface $answer);

}
