<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avro\SupportBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Avro\SupportBundle\Model\QuestionInterface;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
abstract class Question implements QuestionInterface
{
    /**
     * @Gedmo\Slug(fields={"title"})
     * @ODM\String
     */
    protected $slug;

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

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->categorys = new ArrayCollection();
    }

    public function getTitle()
    {
        return $this->title;
    }

	public function setTitle($title) {
		$this->title = $title;
	}

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
        return $this;
    }

    public function getAuthorGravatar()
    {
        return $this->authorGravatar;
    }

    public function setAuthorGravatar($authorGravatar)
    {
        $this->authorGravatar = $authorGravatar;
        return $this;
    }

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

	public function getBody() {
			return $this->body;
	}

	public function setBody($body) {
			$this->body = $body;
	}

    public function getHasResponse()
    {
        return $this->hasResponse;
    }

    public function setHasResponse($hasResponse)
    {
        $this->hasResponse = $hasResponse;
        return $this;
    }

	public function getViews() {
			return $this->views;
	}

	public function incrementViews() {
			$this->views++;
	}

	public function getIsPublic() {
			return $this->isPublic;
	}

	public function setIsPublic($isPublic) {
			$this->isPublic = $isPublic;
	}

	public function getIsSolved() {
			return $this->isSolved;
	}

	public function setIsSolved($isSolved) {
		$this->isSolved = $isSolved;
	}

	public function getSolvedAt() {
			return $this->solvedAt;
	}

	public function setSolvedAt($solvedAt) {
			$this->solvedAt = $solvedAt;
	}

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers($answers)
    {
        $this->answers = $answers;
        return $this;
    }

    public function addAnswer(\Avro\SupportBundle\Model\AnswerInterface $answer)
    {
        $this->answers[] = $answer;
    }

    public function removeAnswer(\Avro\SupportBundle\Model\AnswerInterface $answer)
    {
        $this->answers->removeElement($answer);
    }

    public function getCategorys()
    {
        return $this->categorys;
    }

    public function setCategorys($categorys)
    {
        $this->categorys = $categorys;
        return $this;
    }

    public function addCategory(\Avro\SupportBundle\Model\CategoryInterface $category)
    {
        $this->categorys[] = $category;
    }

    public function removeCategory(\Avro\SupportBundle\Model\CategoryInterface $category)
    {
        $this->categorys->removeElement($category);
    }

	public function getCreatedAt() {
			return $this->createdAt;
	}

	public function setCreatedAt($createdAt) {
			$this->createdAt = $createdAt;
	}

	public function getUpdatedAt() {
			return $this->updatedAt;
	}

	public function setUpdatedAt($updatedAt) {
			$this->updatedAt = $updatedAt;
	}

}
