<?php
namespace Avro\SupportBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Avro\SupportBundle\Event\QuestionEvent;

class QuestionListener {

    protected $mailer;
    protected $context;

    public function __construct($mailer, $context) {
        $this->mailer = $mailer;
        $this->context = $context;
    }

    public function create(QuestionEvent $event) {
        $question = $event->getQuestion();

        if ($this->context->isGranted("ROLE_USER")) {
            $user = $this->context->getToken()->getUser();
            $question->setAuthorId($user->getId());
            $question->setAuthorName($user->getFullName());
            $question->setAuthorEmail($user->getEmail());
            $question->setAuthorGravatar(md5($user->getEmail()));
        }

        $question->setCreatedAt(new \DateTime('now'));
    }

    public function created(QuestionEvent $event) {
        $question = $event->getQuestion();

        $this->mailer->sendQuestionCreatedEmail($question);
    }

    public function update(QuestionEvent $event) {
        $question = $event->getQuestion();
        $question->setUpdatedAt(new \DateTime('now'));
    }

    public function updated(QuestionEvent $event) {
        //$question = $event->getQuestion();
    }

}
