<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avro\SupportBundle\Model;

/**
 * QuestionInterace
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
interface QuestionInterface
{
    public function setTitle($title);

    public function getTitle();

    public function setBody($body);

    public function getBody();
}
