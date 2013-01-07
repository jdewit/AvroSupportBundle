<?php
namespace Avro\SupportBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Avro\SupportBundle\Model\AnswerInterface;
use Avro\SupportBundle\Model\QuestionInterface;

/**
 * An event that occurs related to a answer.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class AnswerEvent extends Event
{
    private $answer;
    private $question;

    /**
     * Constructs an event.
     *
     * @param \Avro\SupportBundle\Model\AnswerInterface $answer
     * @param \Avro\SupportBundle\Model\QuestionInterface $question
     */
    public function __construct(AnswerInterface $answer, QuestionInterface $question)
    {
        $this->answer = $answer;
        $this->question = $question;
    }

    /**
     * Returns the answer for this event.
     *
     * @return \Avro\SupportBundle\Model\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
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
