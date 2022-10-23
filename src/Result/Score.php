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
    protected float $score = 0.0;

    /**
     * @var SingleScore[]
     */
    protected array $scores = [];

    public function add(SingleScore $score): void
    {
        $this->scores[] = $score;
        $this->score += $score->getScore();
    }

    public function score(): float
    {
        return $this->score;
    }

    /**
     * @return SingleScore[]
     */
    public function getScores(): array
    {
        return $this->scores;
    }
}
