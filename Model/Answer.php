<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avro\SupportBundle\Model;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
abstract class Answer implements AnswerInterface
{
    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
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

    public function getIsPublic()
    {
        return $this->isPublic;
    }

    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
