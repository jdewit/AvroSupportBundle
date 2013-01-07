<?php
namespace Avro\SupportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Avro\SupportBundle\Entity\Answer
 * 
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 * 
 * @ORM\Entity
 * @ORM\Table(name="support_answer")
 * @ORM\HasLifecycleCallbacks
 */
class Answer 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    protected $id;

    /**
     * @var \Avro\SupportBundle\Entity\Question
     *
     * @ORM\ManyToOne(targetEntity="Avro\SupportBundle\Entity\Question")
     */
    protected $question;

    /**
     * @var \Avro\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Avro\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $body;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isPublic;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isSolution;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isDeleted = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /** 
     * @ORM\PrePersist 
     */
    public function PrePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    /** 
     * @ORM\PreUpdate 
     */
    public function PreUpdate()
    {
       $this->updatedAt= new \DateTime('now');
    }

    public function __construct() 
    {
    }

    /**
     * Get answer id
     *
     * @return integer
     */   
    public function getId()
    {
        return $this->id;
    }

 
    /**
     * Get question
     * 
     * @return Avro\SupportBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question
     *
     * @param manyToOne $question
     */
    public function setQuestion(\Avro\SupportBundle\Entity\Question $question = null)
    {
        $this->question = $question;
    }     
 
    /**
     * Get author
     * 
     * @return Avro\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param manyToOne $author
     */
    public function setAuthor(\Avro\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    }     
 
    /**
     * Get body
     * 
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }    

 
    /**
     * Get isPublic
     * 
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }
    
    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }    

 
    /**
     * Get isSolution
     * 
     * @return boolean 
     */
    public function getIsSolution()
    {
        return $this->isSolution;
    }
    
    /**
     * Set isSolution
     *
     * @param boolean $isSolution
     */
    public function setIsSolution($isSolution)
    {
        $this->isSolution = $isSolution;
    }    

    /**
    * Set createdAt
    *
    * @param datetime $createdAt
    */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get isDeleted
     * 
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }
    
    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }  

    /**
     * Set deletedAt
     *
     * @param datetime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * Get deletedAt
     *
     * @return datetime $deletedAt
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * String output
     */
    public function __toString()
    {
        return $this->question;
        //return $this->author;
        //return $this->body;
        //return $this->isPublic;
        //return $this->isSolution;
    } 
}

