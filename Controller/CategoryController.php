<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CategoryController extends containerAware
{
    /**
     */
    public function listAction()
    {
        $paginator = $this->container->get('avro_support.category_manager')->getCategories();

        return array(
            'paginator' => $paginator
        );
    }

    /**
     */
    public function newAction()
    {
        $form = $this->container->get('avro_support.category.form');
        $formHandler = $this->container->get('avro_support.category.form.handler');

        $process = $formHandler->process();
        if ($process) {
            $translator = $this->container->get('translator');

            $this->get('session')->getFlashBag()->set('success', 'avro_support.category.created.flash');
            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.category.created.flash', array(), 'AvroSupportBundle'));

            $category = $form->getData();

            return new RedirectResponse($this->get('router')->generate('avro_support_support_index'));
        }

        $isUser = $this->get('security.context')->isGranted($this->container->getParameter('avro_support.min_role'));

        return array(
            'form' => $form->createView(),
            'isUser' => $isUser
        );
    }

    /**
     */
    public function editAction($slug)
    {
        $category = $this->container->get('avro_support.category_manager')->findBySlug($slug);
        $form = $this->container->get('avro_support.category.form');
        $formHandler = $this->container->get('avro_support.category.form.handler');

        $process = $formHandler->process($category);
        if ($process) {
            $category = $form->getData('category');
            $translator = $this->container->get('translator');

            $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.category.updated.flash', array(), 'AvroSupportBundle'));

            return new RedirectResponse($this->container->get('router')->generate('avro_support_category_list'));
        }

        return array(
            'form' => $form->createView(),
            'category' => $category,
        );
    }

    /**
     */
    public function deleteAction($id)
    {
        $categoryManager = $this->container->get('avro_support.category_manager');
        $translator = $this->container->get('translator');
        $category = $categoryManager->find($id);

        $category = $categoryManager->delete($category);

        $this->container->get('session')->getFlashBag()->set('success', $translator->trans('avro_support.category.deleted.flash', array(), 'AvroSupportBundle'));

        return new RedirectResponse($this->container->get('router')->generate('avro_support_category_list'));
    }
}
