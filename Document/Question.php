<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avro\SupportBundle\Document;

use Avro\SupportBundle\Model\Question as BaseQuestion;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 *
 * @ODM\Document
 */
class Question extends BaseQuestion
{
    /**
     * @ODM\Id(strategy="auto")
     */
    public $id;

    /**
     * @ODM\String
     */
    protected $title;

    /**
     * @ODM\String
     */
    protected $body;

    /**
     * Author authorGravatar
     *
     * @ODM\String
     */
    protected $authorGravatar;

    /**
     * Author authorId
     *
     * @ODM\String
     */
    protected $authorId;

    /**
     * Author authorName
     *
     * @ODM\String
     */
    protected $authorName = 'anonymous';

    /**
     * Author authorEmail
     *
     * @ODM\String
     */
    protected $authorEmail;

    /**
     * @ODM\EmbedMany(targetDocument="Application\SupportBundle\Document\Answer")
     */
    protected $answers;

    /**
     * @ODM\ReferenceMany(targetDocument="Category", simple=true)
     */
    protected $categorys;

    /**
     * @ODM\Boolean
     */
    protected $hasResponse;

    /**
     * @ODM\Boolean
     */
    protected $isPublic = true;

    /**
     * @ODM\Boolean
     */
    protected $isSolved = false;

    /**
     * @ODM\Int
     */
    protected $views = 0;

    /**
     * @ODM\Date
     */
    protected $solvedAt;

    /**
     * @ODM\Date
     */
    protected $createdAt;

    /**
     * @ODM\Date
     */
    protected $updatedAt;

}
