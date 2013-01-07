<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\SupportBundle\Document;

use Doctrine\Common\Persistence\ObjectManager;

use Avro\SupportBundle\Doctrine\AnswerManager as BaseAnswerManager;

/**
 * Answer Manager
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class AnswerManager extends BaseAnswerManager
{
    public function __construct(ObjectManager $om, $class)
    {
        parent::__construct($om, $class);
    }
}
