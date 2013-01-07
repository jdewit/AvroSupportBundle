<?php
namespace Avro\SupportBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Avro\SupportBundle\Event\QuestionEvent;
use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Model\QuestionManagerInterface;
/*
 * Question Form Handler
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionFormHandler
{
    protected $event = 'update';
    protected $form;
    protected $request;
    protected $dispatcher;
    protected $questionManager;

    public function __construct(Form $form, Request $request, EventDispatcherInterface $dispatcher, QuestionManagerInterface $questionManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->dispatcher = $dispatcher;
        $this->questionManager = $questionManager;
    }

    /*
     * Process the form
     *
     * @param Question
     *
     * @return boolean true if successful
     * @return array $errors if unsuccessful
     */
    public function process(QuestionInterface $question = null)
    {
        if (null === $question) {
            $this->event = 'create';
            $question = $this->questionManager->create();
        }

        $this->form->setData($question);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {

                $this->dispatcher->dispatch(sprintf('avro_support.question_%s', $this->event), new QuestionEvent($question));

                $this->questionManager->update($question);

                $this->dispatcher->dispatch(sprintf('avro_support.question_%sd', $this->event), new QuestionEvent($question));

                return true;
            }
        }

        return false;
    }
}
