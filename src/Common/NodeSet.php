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

use GraphAware\Common\Type\NodeInterface;

class NodeSet extends ObjectSet
{
    /**
     * @param \GraphAware\Common\Type\NodeInterface $node
     */
    public function add(NodeInterface $node)
    {
        if (parent::valid($node) && !$this->contains($node)) {
            $this->elements[$node->identity()] = $node;
        }
    }

    /**
     * @param $key
     *
     * @return \GraphAware\Common\Type\NodeInterface
     */
    public function get($key)
    {
        return array_values($this->elements)[$key];
    }

    /**
     * @return \GraphAware\Common\Type\NodeInterface[]
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
     * @param \GraphAware\Common\Type\NodeInterface $node
     *
     * @return bool
     */
    public function contains(NodeInterface $node)
    {
        return array_key_exists($node->identity(), $this->elements);
    }
}
