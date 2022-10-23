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

class Rating
{
    protected float $rating;

    protected int $userNodeId;

    public function __construct(float $rating, int $userNodeId)
    {
        $this->rating = (float) $rating;
        $this->userNodeId = (int) $userNodeId;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getId(): int
    {
        return $this->userNodeId;
    }
}
