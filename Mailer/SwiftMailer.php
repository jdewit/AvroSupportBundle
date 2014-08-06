<?php
namespace Avro\SupportBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class SwiftMailer extends BaseMailer
{
    protected $mailer;
    protected $router;
    protected $templating;
    protected $parameters;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->mailer = $mailer;
        $this->context = $context;
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
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
