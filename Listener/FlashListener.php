<?php

namespace Avro\SupportBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashListener implements EventSubscriberInterface
{
    private $session;
    private $translator;

    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'avro_support.question.persisted' => 'addSuccessFlash',
            'avro_support.question.updated' => 'addSuccessFlash',
            'avro_support.question.deleted' => 'addSuccessFlash',
            'avro_support.answer.added' => 'addSuccessFlash',
            'avro_support.answer.removed' => 'addSuccessFlash',
        );
    }

    public function addSuccessFlash(Event $event)
    {
        $this->session->getFlashBag()->add('success', $this->trans(str_replace('avro_support\.', '', $event->getName()) . '.flash'));
    }

    private function trans($message, array $params = array())
    {
        return $this->translator->trans($message, $params, 'AvroSupportBundle');
    }
}
