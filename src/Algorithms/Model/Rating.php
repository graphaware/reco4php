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
    /**
     * @var float
     */
    protected $rating;

    /**
     * @var int
     */
    protected $userNodeId;

    public function __construct($rating, $userNodeId)
    {
        $this->rating = (float) $rating;
        $this->userNodeId = (int) $userNodeId;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->userNodeId;
    }
}
