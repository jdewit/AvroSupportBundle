<?php
namespace Avro\SupportBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Avro\SupportBundle\Model\AnswerManagerInterface;
use Avro\SupportBundle\Model\QuestionManagerInterface;
use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Event\AnswerEvent;

/*
 * Answer Form Handler
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class AnswerFormHandler
{
    protected $form;
    protected $request;
    protected $dispatcher;
    protected $answerManager;

    public function __construct(Form $form, Request $request, EventDispatcherInterface $dispatcher, AnswerManagerInterface $answerManager, QuestionManagerInterface $questionManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->dispatcher = $dispatcher;
        $this->answerManager = $answerManager;
        $this->questionManager = $questionManager;
    }

    /*
     * Process the form
     *
     * @param Answer
     *
     * @return boolean true if successful
     */
    public function process(QuestionInterface $question)
    {
        $answer = $this->answerManager->create();

        $this->form->setData($answer);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->dispatcher->dispatch('avro_support.answer_create', new AnswerEvent($answer, $question));

                $question->addAnswer($answer);

                $this->questionManager->update($question);

                $this->dispatcher->dispatch('avro_support.answer_created', new AnswerEvent($answer, $question));

                return true;
            }
        }

        return false;
    }
}
