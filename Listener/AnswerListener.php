<?php
namespace Avro\SupportBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Avro\SupportBundle\Event\AnswerEvent;

class AnswerListener {

    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    public function add(AnswerEvent $event) {
        $answer = $event->getAnswer();

        if ($this->context->isGranted("ROLE_USER")) {
            $user = $this->context->getToken()->getUser();
            $answer->setAuthorId($user->getId());
            $answer->setAuthorName($user->getFullName());
            $answer->setAuthorEmail($user->getEmail());
        }

        $answer->setCreatedAt(new \DateTime('now'));
    }

    public function update(AnswerEvent $event) {
        $answer = $event->getAnswer();
        $answer->setUpdatedAt(new \DateTime('now'));
    }

    public function updated(AnswerEvent $event) {
        //$answer = $event->getAnswer();
    }

}
