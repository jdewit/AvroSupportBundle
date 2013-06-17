<?php
namespace Avro\SupportBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Avro\SupportBundle\Event\QuestionEvent;

class QuestionListener {

    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    public function persist(QuestionEvent $event) {
        $question = $event->getQuestion();

        if ($this->context->isGranted("ROLE_USER")) {
            $user = $this->context->getToken()->getUser();
            $question->setAuthorId($user->getId());
            $question->setAuthorName($user->getFullName());
            $question->setAuthorEmail($user->getEmail());
        }

    }

    public function update(QuestionEvent $event) {
        $question = $event->getQuestion();
        $question->setUpdatedAt(new \DateTime('now'));
    }

    public function updated(QuestionEvent $event) {
        //$question = $event->getQuestion();
    }

}
