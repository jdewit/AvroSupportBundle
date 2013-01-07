<?php
namespace Avro\SupportBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Model\AnswerInterface;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class Mailer implements MailerInterface
{
    protected $mailer;
    protected $context;
    protected $router;
    protected $templating;
    protected $parameters;

    public function __construct($mailer, $context, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->mailer = $mailer;
        $this->context = $context;
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
    }

    public function sendQuestionCreatedEmail(QuestionInterface $question)
    {
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

    public function sendAnswerCreatedEmail(QuestionInterface $question, AnswerInterface $answer)
    {
        $rendered = $this->templating->render('AvroSupportBundle:Email:user/answer_created.html.twig', array(
            'answer' => $answer,
            'question' => $question,
            'email_signature' => $this->parameters['email_signature']
        ));

        $this->sendEmailMessage($rendered, $this->parameters['from_email'], $question->getAuthorEmail());
    }

    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body)
        ;

        $this->mailer->send($message);
    }
}
