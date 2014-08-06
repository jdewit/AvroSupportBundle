<?php
namespace Avro\SupportBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;

use Hip\MandrillBundle\Message;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class MandrillMailer extends BaseMailer
{
    protected $mandrill;
    protected $router;
    protected $templating;
    protected $parameters;

    public function __construct($mandrill, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->mandrill = $mandrill;
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

        $message = new Message();

        $message->setFromEmail($fromEmail)
            //->setFromName('Management Driver')
            ->addTo($toEmail)
            ->setSubject($subject)
            ->setText($body);
            //->setHtml($body);
            //->setSubaccount('Password');

        $this->mandrill->send($message);
    }
}
