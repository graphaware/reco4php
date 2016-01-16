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

use GraphAware\Common\Type\NodeInterface;

class Recommendation
{
    /**
     * @var \GraphAware\Common\Type\NodeInterface
     */
    protected $item;

    /**
     * @var \GraphAware\Reco4PHP\Result\Score[]
     */
    protected $scores = [];

    /**
     * @var float;
     */
    protected $totalScore = 0.0;

    /**
     * Recommendation constructor.
     * @param \GraphAware\Common\Type\NodeInterface $item
     * @param \GraphAware\Reco4PHP\Result\Score|null $score
     */
    public function __construct(NodeInterface $item, Score $score = null)
    {
        $this->item = $item;
        if ($score) {
            $this->addScore($score);
        }
    }

    /**
     * @param \GraphAware\Reco4PHP\Result\Score $score
     */
    public function addScore(Score $score)
    {
        $this->scores[] = $score;
        $this->totalScore += $score->score();
    }

    /**
     * @return float
     */
    public function totalScore()
    {
        return (float) $this->totalScore;
    }

    /**
     * @return \GraphAware\Reco4PHP\Result\Score[]
     */
    public function scores()
    {
        return $this->scores;
    }

    /**
     * @return \GraphAware\Common\Type\NodeInterface
     */
    public function item()
    {
        return $this->item;
    }
}