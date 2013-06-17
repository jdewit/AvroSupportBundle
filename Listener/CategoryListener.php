<?php
namespace Avro\SupportBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Avro\SupportBundle\Event\CategoryEvent;

class CategoryListener {

    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    public function create(CategoryEvent $event) {
    //    $category = $event->getCategory();

    }

    public function created(CategoryEvent $event) {
    //    $category = $event->getCategory();

    }


    public function update(CategoryEvent $event) {
      //  $category = $event->getCategory();
    }

    public function updated(CategoryEvent $event) {
        //$category = $event->getCategory();
    }

}
