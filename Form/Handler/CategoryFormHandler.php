<?php
namespace Avro\SupportBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Avro\SupportBundle\Model\CategoryManagerInterface;
use Avro\SupportBundle\Model\QuestionManagerInterface;
use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Event\CategoryEvent;

/*
 * Category Form Handler
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CategoryFormHandler
{
    protected $event = 'update';
    protected $form;
    protected $request;
    protected $dispatcher;
    protected $categoryManager;

    public function __construct(Form $form, Request $request, $dispatcher, CategoryManagerInterface $categoryManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->dispatcher = $dispatcher;
        $this->categoryManager = $categoryManager;
    }

    /*
     * Process the form
     *
     * @param Category
     *
     * @return boolean true if successful
     * @return array $errors if unsuccessful
     */
    public function process($category = null)
    {
        if (!$category) {
            $this->event = 'create';
            $category = $this->categoryManager->create();
        }

        $this->form->setData($category);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->dispatcher->dispatch(sprintf('avro_support.category_%s', $this->event), new CategoryEvent($category));

                $this->categoryManager->update($category);

                $this->dispatcher->dispatch(sprintf('avro_support.category_%sd', $this->event), new CategoryEvent($category));

                return true;
            }
        }

        return false;
    }
}
