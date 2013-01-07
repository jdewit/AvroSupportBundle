<?php
namespace Avro\SupportBundle\Event;

use Avro\SupportBundle\Model\CategoryInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a category.
 *
 * @autho Joris de Wit <joris.w.dewit@gmail.com>
 */
class CategoryEvent extends Event
{
    private $category;

    /**
     * Constructs an event.
     *
     * @param \Avro\SupportBundle\Entity\category $category
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the category for this event.
     *
     * @return \Avro\SupportBundle\Model\category
     */
    public function getCategory()
    {
        return $this->category;
    }
}
