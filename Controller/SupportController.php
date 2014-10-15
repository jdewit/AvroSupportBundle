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
        $context = $this->container->get('security.context');
        $paginator = $this->container->get('knp_paginator');

        $user = $context->getToken()->getUser();

        if (!is_object($user)) {
            throw new \Exception('No user found');
        }

        $faqQuery = $this->container->get('avro_support.question.manager')->getFaqQuestionsQuery();
        $faqQuestions = $paginator->paginate(
            $faqQuery,
            $request->query->get('page', 1),
            20
        );

        $userQuestionsQuery = $this->container->get('avro_support.question.manager')->getUsersQuestionsQuery($user->getId());
        $userQuestions = $paginator->paginate(
            $userQuestionsQuery,
            $request->query->get('page', 1),
            20
        );

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Support:index.html.twig', array(
            'faqQuestions' => $faqQuestions,
            'userQuestions' => $userQuestions,
            'adminRole' => $this->container->getParameter('avro_support.admin_role')
        ));
    }

    /**
     * Search for questions
     */
    public function searchAction(Request $request)
    {
        $query = $this->container->get('request')->query->get('q');
        $paginator = $this->container->get('knp_paginator');
        $context = $this->container->get('security.context');

        $user = $context->getToken()->getUser();

        $searchQuery = $this->container->get('avro_support.question.manager')->getSearchQuery($query, $user->getId());

        $questions = $paginator->paginate(
            $searchQuery,
            $request->query->get('page', 1),
            20
        );

		return $this->container->get('templating')->renderResponse('AvroSupportBundle:Question:list.html.twig', array(
            'questions' => $questions,
            'query' => $query,
            'filter' => 'search',
            'adminRole' => $this->container->getParameter('avro_support.admin_role')
        ));
    }

//    /**
//     * Search by category
//     *
//     * @Template()
//     */
//    public function searchByCategoryAction($slug)
//    {
//        $category = $this->container->get('avro_support.category.manager')->findBySlug($slug);
//
//        $pagination = $this->container->get('avro_support.question.manager')->searchByCategory($category->getId());
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
//        $pagination = $this->container->get('avro_support.question.manager')->searchByUser($id);
//
//        return array(
//            'pagination' => $pagination,
//        );
//    }
}

