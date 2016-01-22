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
    protected $score = 0.0;

    /**
     * @var \GraphAware\Reco4PHP\Result\SingleScore[]
     */
    protected $scores;

    /**
     * @param \GraphAware\Reco4PHP\Result\SingleScore $score
     */
    public function add(SingleScore $score)
    {
        $this->scores[] = $score;
        $this->score += (float) $score->getScore();
    }

    /**
     * @return float
     */
    public function score()
    {
        return $this->score;
    }

    /**
     * @return \GraphAware\Reco4PHP\Result\SingleScore[]
     */
    public function getScores()
    {
        return $this->scores;
    }
}