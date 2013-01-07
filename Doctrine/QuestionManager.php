<?php
namespace Avro\SupportBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Model\QuestionManagerInterface;

/*
 * Managing class for Question entity
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionManager implements QuestionManagerInterface
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
     * Creates a Question
     *
     * @return Question
     */
    public function create()
    {
        $class = $this->getClass();

        $question = new $class();

        return $question;
    }

    /**
     * Updates a Question
     *
     * @param Question $question
     * @param boolean $andFlush Flush om if true
     */
    public function update(QuestionInterface $question, $andFlush = true)
    {
        $this->om->persist($question);

        if ($andFlush) {
            $this->om->flush();
        }
    }

    /**
     * Permanently delete one Question
     *
     * @param Question $question
     * @param boolean $andFlush Flush om if true
     */
    public function delete(QuestionInterface $question)
    {
        $this->om->remove($question);
        $this->om->flush();

        return true;
    }

    /**
     * Close a Question
     *
     * @param QuestionInterface $question
     */
    public function close(QuestionInterface $question)
    {
        $question->setIsSolved(true);
        $question->setSolvedAt(new \Datetime('now'));

        $this->om->persist($question);
        $this->om->flush();

        return true;
    }

    /**
     * Find one question by id
     *
     * @param string $id
     * @return Question
     */
    public function find($id)
    {
        $question = $this->repository->find($id);

        return $question;
    }

    /**
     * Find one question by slug
     *
     * @param string $slug
     * @return Question
     */
    public function findBySlug($slug)
    {
        $question = $this->repository->findOneBy(array('slug' => $slug));

        return $question;
    }

    /**
     * Show one question by slug and increment views
     *
     * @param string $slug
     * @return Question
     */
    public function show($slug)
    {
        $question = $this->repository->findOneBy(array('slug' => $slug));

        if (!$question) {
            throw new NotFoundHttpException('Question not found.');
        }

        $question->incrementViews();
        $this->update($question);

        return $question;
    }

    /**
     * Find one question by criteria
     *
     * @parameter array $criteria
     * @return Question
     */
    public function findOneBy($criteria = array())
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find questions by criteria
     *
     * @param array $criteria
     * @param array $sortBy
     * @param string $limit
     * @return Questions
     */
    public function findBy(array $criteria = null, array $sortBy = null, $limit = null)
    {
        return $this->repository->findBy($criteria, $sortBy, $limit);
    }

    /**
     * Find active questions
     *
     * @return Questions
     */
    public function findAllActive()
    {
        return $this->repository->findBy(array('isDeleted' => false));
    }
}

