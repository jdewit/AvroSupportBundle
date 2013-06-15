<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Support controller.
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class SupportController extends ContainerAware
{
    /**
     * Show support home page
     */
    public function indexAction(Request $request)
    {
        $faqQuestions = $this->container->get('avro_support.question_manager')->getFAQ();

        $userQuestions = $this->container->get('avro_support.question_manager')->getFAQ();

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Support:index.html.twig', array(
            'faqQuestions' => $faqQuestions,
            'userQuestions' => $userQuestions,
        ));
    }

    /**
     * Search for questions
     */
    public function searchAction()
    {
        $query = $this->container->get('request')->query->get('q');

        $pagination = $this->container->get('avro_support.question_manager')->search($query);

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Support:index.html.twig', array(
            'pagination' => $pagination,
            'query' => $query
        ));
    }

//    /**
//     * Search by category
//     *
//     * @Template()
//     */
//    public function searchByCategoryAction($slug)
//    {
//        $category = $this->container->get('avro_support.category_manager')->findBySlug($slug);
//
//        $pagination = $this->container->get('avro_support.question_manager')->searchByCategory($category->getId());
//
//        return array(
//            'pagination' => $pagination,
//            'category' => $category
//        );
//    }
//
//    /**
//     * Search by user
//     *
//     * @Template()
//     */
//    public function searchByUserAction($id)
//    {
//        $pagination = $this->container->get('avro_support.question_manager')->searchByUser($id);
//
//        return array(
//            'pagination' => $pagination,
//        );
//    }
}

