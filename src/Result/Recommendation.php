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

use GraphAware\Common\Type\Node;

class Recommendation
{
    /**
     * @var \GraphAware\Common\Type\Node
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
     * @param \GraphAware\Common\Type\Node $item
     */
    public function __construct(Node $item)
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
     * @param \GraphAware\Reco4PHP\Result\SingleScore[]
     */
    public function addScores(array $scores)
    {
        foreach ($scores as $name => $score) {
            $this->addScore($name, $score);
        }
    }

    /**
     * @return \GraphAware\Reco4PHP\Result\SingleScore[]
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * @param string $key
     *
     * @return \GraphAware\Reco4PHP\Result\Score
     */
    public function getScore($key)
    {
        if (!array_key_exists($key, $this->scores)) {
            throw new \InvalidArgumentException(sprintf('The recommendation does not contains a score named "%s"', $key));
        }

        return $this->scores[$key];
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
        return $this->totalScore;
    }

    /**
     * @return \GraphAware\Common\Type\Node
     */
    public function item()
    {
        return $this->item;
    }
}
