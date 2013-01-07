<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Category controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CategoryController extends controller
{
    /**
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function listAction()
    {
        $paginator = $this->container->get('avro_support.category_manager')->getCategories();

        return array(
            'paginator' => $paginator
        );
    }

    /**
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction()
    {
        $form = $this->container->get('avro_support.category.form');
        $formHandler = $this->container->get('avro_support.category.form.handler');
        $process = $formHandler->process();
        if ($process) {
            $this->get('session')->getFlashBag()->set('success', 'Category created.');

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
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function editAction($slug)
    {
        $category = $this->container->get('avro_support.category_manager')->findBySlug($slug);
        $form = $this->container->get('avro_support.category.form');
        $formHandler = $this->container->get('avro_support.category.form.handler');

        $process = $formHandler->process($category);
        if ($process) {
            $category = $form->getData('category');
            $this->container->get('session')->getFlashBag()->set('success', ' Category updated.');

            return new RedirectResponse($this->container->get('router')->generate('avro_support_category_list'));
        }

        return array(
            'form' => $form->createView(),
            'category' => $category,
        );
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id)
    {
        $categoryManager = $this->container->get('avro_support.category_manager');
        $category = $categoryManager->find($id);

        $category = $categoryManager->delete($category);

        $this->container->get('session')->getFlashBag()->set('success', ' Category deleted.');

        return new RedirectResponse($this->container->get('router')->generate('avro_support_category_list'));
    }
}
