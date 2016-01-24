<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Algorithms\Model;

use GraphAware\Common\Type\NodeInterface;

class Rating
{
    protected $rating;

    protected $node;

    public function __construct($rating, NodeInterface $node)
    {
        $this->rating = (float) $rating;
        $this->node = $node;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return \GraphAware\Common\Type\NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }
}