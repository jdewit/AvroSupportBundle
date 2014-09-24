<?php

namespace Avro\SupportBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Support admin controller.
 *
 * @author Helmut Wollenberg <helmut.wollenberg@wardell.biz>
 */
class SupportAdminController extends ContainerAware
{
    /**
     * Show admin page with list of questions
     */
    public function listAction(Request $request, $filter)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $paginator = $this->container->get('knp_paginator');

        $query = $this->container->get('avro_support.question.manager')->getAllQuestionsQuery($filter);

        $questions = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        return $this->container->get('templating')->renderResponse('AvroSupportBundle:SupportAdmin:list.html.twig', array(
            'questions' => $questions,
            'filter' => $filter,
            'adminRole' => $this->container->getParameter('avro_support_admin_role')
        ));
    }

    /**
     * Search for questions
     */
    public function searchAction(Request $request)
    {
        $query = $this->container->get('request')->query->get('q');
        $paginator = $this->container->get('knp_paginator');

        $searchQuery = $this->container->get('avro_support.question.manager')->getAdminSearchQuery($query);

        $questions = $paginator->paginate(
            $searchQuery,
            $request->query->get('page', 1),
            20
        );

        return $this->container->get('templating')->renderResponse('AvroSupportBundle:SupportAdmin:list.html.twig', array(
            'questions' => $questions,
            'query' => $query,
            'filter' => 'search',
            'adminRole' => $this->container->getParameter('avro_support_admin_role')
        ));
    }
}

