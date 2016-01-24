<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Common;

abstract class BaseSet implements Set
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $elements = [];

    protected function __construct($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException(sprintf('The classname %s does not exist', $className));
        }

        $this->className = $className;
    }

    /**
     * @param object $element
     * @return bool
     */
    protected function valid($element)
    {
        return $element instanceof $this->className;
    }
}