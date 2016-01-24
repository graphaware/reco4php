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
     * @var float
     */
    protected $totalScore = 0.0;

    /**
     * Recommendation constructor.
     *
     * @param \GraphAware\Common\Type\NodeInterface  $item
     * @param \GraphAware\Reco4PHP\Result\Score|null $score
     */
    public function __construct(NodeInterface $item)
    {
        $this->item = $item;
    }

    /**
     * @param string                                  $name
     * @param \GraphAware\Reco4PHP\Result\SingleScore $score
     */
    public function addScore($name, SingleScore $score)
    {
        $this->getScoreOrCreate($name)->add($score);
        $this->totalScore += $score->getScore();
    }

    /**
     * @param \GraphAware\Reco4PHP\Result\Score[]
     */
    public function addScores(array $scores)
    {
        foreach ($scores as $name => $score) {
            $this->scores[$name] = $score;
        }
    }

    public function getScores()
    {
        return $this->scores;
    }

    private function getScoreOrCreate($name)
    {
        if (!array_key_exists($name, $this->scores)) {
            $this->scores[$name] = new Score($name);
        }

        return $this->scores[$name];
    }

    /**
     * @return float
     */
    public function totalScore()
    {
        $score = 0.0;
        foreach ($this->scores as $sc) {
            $score += $sc->score();
        }

        return $score;
    }

    /**
     * @return \GraphAware\Common\Type\NodeInterface
     */
    public function item()
    {
        return $this->item;
    }
}
