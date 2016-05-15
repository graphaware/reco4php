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

use GraphAware\Common\Type\Node;

class NodeSet extends ObjectSet
{
    /**
     * @param \GraphAware\Common\Type\Node $node
     */
    public function add(Node $node)
    {
        if (parent::valid($node) && !$this->contains($node)) {
            $this->elements[$node->identity()] = $node;
        }
    }

    /**
     * @param $key
     *
     * @return \GraphAware\Common\Type\Node
     */
    public function get($key)
    {
        return array_values($this->elements)[$key];
    }

    /**
     * @return \GraphAware\Common\Type\Node[]
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->elements);
    }

    /**
     * @param \GraphAware\Common\Type\Node $node
     *
     * @return bool
     */
    public function contains(Node $node)
    {
        return array_key_exists($node->identity(), $this->elements);
    }
}
