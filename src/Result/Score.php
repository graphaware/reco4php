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

class Score
{
    /**
     * @var float
     */
    protected $score;

    /**
     * @var string
     */
    protected $reason;

    /**
     * Score constructor.
     * @param $score
     * @param null $reason
     */
    public function __construct($score, $reason = null)
    {
        $this->score = (float) $score;
        $this->reason = (string) $reason;
    }

    /**
     * @return float
     */
    public function score()
    {
        return $this->score;
    }


    public function increment($v)
    {
        $this->score += (float) $v;
    }

    /**
     * @return string
     */
    public function reason()
    {
        return $this->reason;
    }
}