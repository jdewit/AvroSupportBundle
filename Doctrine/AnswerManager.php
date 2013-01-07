<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\SupportBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Avro\SupportBundle\Model\AnswerManagerInterface;

/**
 * Answer Manager
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class AnswerManager implements AnswerManagerInterface
{
    protected $om;
    protected $class;

    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        $class = $this->class;

        return new $class();
    }
}
