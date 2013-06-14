<?php
namespace Avro\SupportBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use Avro\SupportBundle\Model\QuestionInterface;
use Avro\SupportBundle\Model\QuestionManagerInterface;

use Avro\SupportBundle\Manager\Doctrine\BaseManager;

/*
 * Managing class for Question entity
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionManager extends BaseManager
{

    public function __construct($om, $paginator, $class)
    {
        parent::__construct($om, $paginator, $class);
    }



    public function getFAQ()
    {
        $qb = $this->om->createQueryBuilder($this->getClass());

        $qb->field('isPublic')->equals(true);
        $qb->sort('views', 'DESC');

        $query = $qb->getQuery();

        return $this->paginate($query);
    }

    /**
     * Get a users questions
     *
     * @param string $userId
     * @param string $constraint
     */
    public function getUsersQuestions($userId, $constraint = false)
    {
        $qb = $this->om->createQueryBuilder($this->getClass());
        $qb->field('authorId')->equals($userId);
        $qb->sort('createdAt', 'DESC');

        switch ($constraint) {
            case 'Open':
                $qb->field('isSolved')->notEqual(true);
            break;
            case 'Solved':
                $qb->field('isSolved')->equals(true);
            break;
        }

        $query = $qb->getQuery();

        return $this->paginate($query);
    }

    /**
     * Should be using solr or something but whatever
     */
    public function search($query)
    {
        $qb = $this->getQueryBuilder();
        $qb->field('isPublic')->equals(true);
        $qb->sort('views', 'DESC');

        $words = str_replace(array(',', '.'), '', $query);
        $words = explode(' ', $query);

        foreach($words as $word) {
            $qb->addAnd($qb->expr()->field('body')->equals(new \MongoRegex('/.*'.$word.'.*/i')));
        }

        $query = $qb->getQuery();

        return $this->paginate($query);
    }

    /*
     * Search by category
     *
     * @param string $categoryId
     */
    public function searchByCategory($categoryId)
    {
        $qb = $this->getQueryBuilder();
        $qb->field('isPublic')->equals(true);
        $qb->field('categorys')->equals($categoryId);
        $qb->sort('views', 'DESC');

        $query = $qb->getQuery();

        return $this->paginate($query);
    }

    /*
     * Search by user
     *
     * @param string $id
     */
    public function searchByUser($id)
    {
        $qb = $this->getQueryBuilder();
        $qb->field('isPublic')->equals(true);
        $qb->field('authorId')->equals($id);
        $qb->sort('views', 'DESC');

        $query = $qb->getQuery();

        return $this->paginate($query);
    }

    /**
     * Remove an answer
     *
     * @param QuestionInterface $question
     * @param string $answerId
     */
    public function removeAnswer($question, $answerId)
    {
        foreach($question->getAnswers() as $answer) {
            if ($answer->getId() == $answerId) {
                $question->removeAnswer($answer);
            }
        }
    }
}

