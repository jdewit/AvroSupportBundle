<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avro\SupportBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Avro\SupportBundle\Model\CategoryInterface;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 *
 */
class Category implements CategoryInterface
{
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ODM\String
     */
    protected $slug;

    /**
     * @ODM\String
     */
    protected $name;

    /**
     * @ODM\Boolean
     */
    protected $isDeleted = false;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
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

    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function __tostring()
    {
        return $this->name;
    }
}
