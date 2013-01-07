<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\SupportBundle\Doctrine;

use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Avro\SupportBundle\Model\CategoryInterface;
use Avro\SupportBundle\Model\CategoryManagerInterface;

/**
 * Category Manager
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CategoryManager implements CategoryManagerInterface
{
    protected $om;
    protected $request;
    protected $paginator;
    protected $class;
    protected $repository;

    public function __construct(ObjectManager $om, Request $request, Paginator $paginator, $class)
    {
        $this->om = $om;
        $this->request = $request;
        $this->paginator = $paginator;
        $this->class = $om->getClassMetadata($class)->getName();
        $this->repository = $om->getRepository($class);
    }

    /**
     * @return fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
    }

    /*
     * Get the queryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->om->createQueryBuilder($this->getClass());
    }

    /*
     * Paginate a query
     */
    public function paginate($query)
    {
        return $this->paginator->paginate(
            $query,
            $this->request->query->get('page', 1),
            10
        );
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        $class = $this->class;

        return new $class();
    }

    /*
     * Update an category
     *
     * @param $category
     */
    public function update(CategoryInterface $category)
    {
        $this->om->persist($category);
        $this->om->flush();

        return true;
    }

    /*
     * Delete a category
     *
     * @param $category
     */
    public function delete(CategoryInterface $category)
    {
        $category->setIsDeleted(true);
        $this->om->persist($category);
        $this->om->flush();

        return true;
    }

    /*
     * Find one category
     *
     * @param string $id
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /*
     * Find all categories
     */
    public function findAll()
    {
        return $this->repository->findBy(array('isDeleted' => false));
    }

    /*
     * Find categorys
     *
     * @param array $constraints
     */
    public function findBy(array $constraints)
    {
        return $this->repository->findBy($constraints);
    }

    /*
     * Find a category by slug
     *
     * @param array $constraints
     */
    public function findBySlug($slug)
    {
        return $this->repository->findOneBy(array('slug' => $slug));
    }
}
