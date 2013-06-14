<?php

namespace Avro\SupportBundle\Manager\Doctrine\BaseManager;

use Doctrine\Common\Persistence\ObjectManager;

use Knp\Component\Pager\Paginator;

use Symfony\Component\HttpFoundation\Request;


/**
 * Base Manager class for Doctrine entities/documents
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 * @license For the full copyright and license information, please view the LICENSE
 */
class BaseManager implements BaseManagerInterface
{
    /**
     * objectManager
     *
     * @var mixed
     */
    protected $om;

    /**
     * dispatcher
     *
     * @var mixed
     */
    protected $dispatcher;

    /**
     * context
     *
     * @var mixed
     */
    protected $context;

    /**
     * The class namespace
     *
     * @var mixed
     */
    protected $class;

    /**
     * The bundle alias
     *
     * @var mixed
     */
    protected $alias;

    /**
     * The document name
     *
     * @var mixed
     */
    protected $name;

    /**
     * The documents event class namespace
     *
     * @var mixed
     */
    protected $eventClass;

    /**
     * Document repository
     *
     * @var mixed
     */
    protected $repository;


    /**
     * dbDriver
     *
     * @var mixed
     */
    protected $dbDriver;

    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher, SecurityContextInterface $context, $class, $alias, $name, $eventClass, $dbDriver = 'mongodb')
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
        $this->context = $context;
        $this->class = $class;
        $this->alias = $alias;
        $this->name = $name;
        $this->eventClass = $eventClass;
        $this->repository = $om->getRepository($class);
        $this->dbDriver = $dbDriver;
    }

    /**
     * @return fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
    }

    /*
     * Flush the entity manager
     *
     * @param boolean $andClear Clears instances of this class from the entity manager
     */
    private function flush($andClear)
    {
        $this->om->flush();

        if ($andClear) {
            $this->om->clear($this->getClass());
        }
    }

    /**
     * Creates a Document
     *
     * @return object Document
     */
    public function create()
    {
        $class = $this->getClass();

        $document = new $class();

           $this->dispatcher->dispatch(sprintf('%s.%s.create', $this->alias, $this->name), new $this->eventClass($document));

        return $document;
    }

    /**
     * Persist a Document
     *
     * @param object  $document
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function persist($document, $andFlush = true, $andClear = false)
    {
        if (!is_object($document)) {
            throw new \InvalidArgumentException('Cannot persist a non object');
        }

           $this->dispatcher->dispatch(sprintf('%s.%s.persist', $this->alias, $this->name), new $this->eventClass($document));

        $document = $this->customize($document);

        $this->om->persist($document);

        if ($andFlush) {
            $this->flush($andClear);
        }

           $this->dispatcher->dispatch(sprintf('%s.%s.persisted', $this->alias, $this->name), new $this->eventClass($document));

        return $document;
    }

    /**
     * Update a Document
     *
     * @param object  $document
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function update($document, $andFlush = true, $andClear = false)
    {
        if (!is_object($document)) {
            throw new \InvalidArgumentException('Cannot persist a non object');
        }

        $this->dispatcher->dispatch(sprintf('%s.%s.update', $this->alias, $this->name), new $this->eventClass($document));

        $document = $this->customize($document);

        $this->om->persist($document);

        if ($andFlush) {
            $this->flush($andClear);
        }

        $this->dispatcher->dispatch(sprintf('%s.%s.updated', $this->alias, $this->name), new $this->eventClass($document));

        return $document;
    }

    /**
     * Delete a Document
     *
     * @param object $document
     */
    public function delete($document)
    {
        $this->dispatcher->dispatch(sprintf('%s.%s.delete', $this->alias, $this->name), new $this->eventClass($document));

        $this->om->remove($document);

        $this->om->flush();

        $this->dispatcher->dispatch(sprintf('%s.%s.deleted', $this->alias, $this->name), new $this->eventClass($document));

        return true;
    }

    /**
     * Customize the object before it is persists/updated
     *
     * @param object $document
     */
    public function customize($document)
    {
        return $document;
    }

    /**
     * Find one document by criteria
     *
     * @param  array  $criteria
     * @return object Document
     */
    public function findOneBy(array $criteria)
    {
        $criteria = $this->filterCriteria($criteria);

        $document = $this->repository->findOneBy($criteria);

        if (!is_object($document)) {
            throw new FlashException('notice', sprintf('Error finding %s. Please try again.', $this->name));
        }

        return $document;
    }

    /**
     * Find one document by id
     *
     * @param  string $id
     * @return object Document
     */
    public function find($id)
    {
        if (!$id) {
            throw new \InvalidArgumentException('Id must be specified.');
        }
        $criteria['id'] = $id;

        return $this->findOneBy($criteria);
    }

    /**
     * Find documents by criteria
     *
     * @param  array $criteria
     * @param  array $orderBy
     * @param  mixed $limit
     * @param  mixed $offset
     * @return array Documents
     */
    public function findBy(array $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        $criteria = $this->filterCriteria($criteria);

        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Find all documents
     *
     * @return array Documents
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /*
     * Paginate a query
     *
     * @param Query $query
     * @param Request $request
     * @return KnpPaginator
     */
    public function paginate($query, Request $request)
    {
        return $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            10
        );
    }
}
