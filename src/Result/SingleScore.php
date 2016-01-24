<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Result;

class SingleScore
{
    /**
     * @var float
     */
    private $score;

    /**
     * @var null|string
     */
    private $reason;

    /**
     * SingleScore constructor.
     *
     * @param float|$score
     * @param null|string $reason
     */
    public function __construct($score, $reason = null)
    {
        $this->score = (float) $score;
        $this->addReason($reason);
    }

    public function addReason($reason = null)
    {
        if (null !== $reason) {
            $this->reason = (string) $reason;
        }
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return null|string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
