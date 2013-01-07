<?php

namespace Avro\SupportBundle\Document;

use Doctrine\Common\Persistence\ObjectManager;
use Avro\SupportBundle\Doctrine\CategoryManager as BaseCategoryManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

/*
 * Managing class for Category entity
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CategoryManager extends BaseCategoryManager
{
    public function __construct(ObjectManager $om, Request $request, Paginator $paginator, $class)
    {
        parent::__construct($om, $request, $paginator, $class);
    }

    public function getCategories()
    {
        $qb = $this->om->createQueryBuilder($this->getClass());
        $qb->field('isDeleted')->notEqual(true);

        $query = $qb->getQuery();

        return $this->paginate($query);
    }

}

