<?php
namespace Avro\SupportBundle\Event;

use Avro\SupportBundle\Model\QuestionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a question.
 *
 * @autho Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionEvent extends Event
{
    private $question;

    /**
     * Constructs an event.
     *
     * @param \Avro\SupportBundle\Entity\Question $question
     */
    public function __construct(QuestionInterface $question)
    {
        $this->question = $question;
    }

    /**
     * Returns the question for this event.
     *
     * @return \Avro\SupportBundle\Model\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
