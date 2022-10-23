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

class ObjectSet implements Set
{
    protected string $className;

    protected array $elements = [];

    final public function __construct(string $className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException(sprintf('The classname %s does not exist', $className));
        }

        $this->className = $className;
    }

    public function add(object $element): void
    {
        if ($this->valid($element) && !in_array($element, $this->elements)) {
            $this->elements[] = $element;
        }
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->elements;
    }

    public function size(): int
    {
        return count($this->elements);
    }

    public function get($key): ?object
    {
        return $this->elements[$key];
    }

    /**
     * @return bool
     */
    protected function valid(object $element)
    {
        return $element instanceof $this->className;
    }
}
