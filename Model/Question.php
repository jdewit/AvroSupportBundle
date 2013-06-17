<?php

namespace Avro\SupportBundle\Model;

use Avro\SupportBundle\Model\AnswerInterface;
use Avro\SupportBundle\Model\CategoryInterface;

use Doctrine\Common\Collections\ArrayCollection;

class Question implements QuestionInterface
{
    protected $id;
    protected $title;
    protected $body;
    protected $authorId;
    protected $authorName = 'anonymous';
    protected $authorEmail;
    protected $answers;
    protected $categories;
    protected $hasResponse;
    protected $isPublic = true;
    protected $isSolved = false;
    protected $views = 0;
    protected $solvedAt;
    protected $createdAt;
    protected $updatedAt;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

	public function setTitle($title) {
		$this->title = $title;
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

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

	public function getGravatar()
	{
		return $gravUrl = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim( $this->authorEmail ) ) ) . '?d=mm&s=16&r=PG';
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

    public function addAnswer(AnswerInterface $answer)
    {
        $this->answers[] = $answer;
    }

    public function removeAnswer(AnswerInterface $answer)
    {
        $this->answers->removeElement($answer);
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function addCategory(CategoryInterface $category)
    {
        $this->categories[] = $category;
    }

    public function removeCategory(CategoryInterface $category)
    {
        $this->categories->removeElement($category);
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

