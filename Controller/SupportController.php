<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Answer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;


/**
 * Support controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class SupportController extends Controller
{
    /**
     * @Template()
     * @Cache(maxage="3600")
     */
    public function indexAction()
    {
        $pagination = $this->container->get('avro_support.question_manager')->getFAQ();

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Template()
     * @Cache(smaxage="3600")
     */
    public function sideWidgetAction()
    {
        $categories = $this->get('avro_support.category_manager')->findAll();

        return array(
            'categories' => $categories,
            'supportEmail' => 'sdafsd@fasdfj'
        );
    }

    /**
     * Search query
     *
     * @Template()
     */
    public function searchAction()
    {
        $query = $this->container->get('request')->query->get('q');

        $pagination = $this->container->get('avro_support.question_manager')->search($query);

        return array(
            'pagination' => $pagination,
            'query' => $query
        );
    }

    /**
     * Search by category
     *
     * @Template()
     */
    public function searchByCategoryAction($slug)
    {
        $category = $this->container->get('avro_support.category_manager')->findBySlug($slug);

        $pagination = $this->container->get('avro_support.question_manager')->searchByCategory($category->getId());

        return array(
            'pagination' => $pagination,
            'category' => $category
        );
    }

    /**
     * Search by user
     *
     * @Template()
     */
    public function searchByUserAction($id)
    {
        $pagination = $this->container->get('avro_support.question_manager')->searchByUser($id);

        return array(
            'pagination' => $pagination,
        );
    }
}

