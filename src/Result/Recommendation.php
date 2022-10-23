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

use Laudis\Neo4j\Types\Node;

class Recommendation
{
    protected Node $item;

    /**
     * @var Score[]
     */
    protected array $scores = [];

    protected float $totalScore = 0.0;

    /**
     * Recommendation constructor.
     */
    public function __construct(Node $item)
    {
        $this->item = $item;
    }

    /**
     * @param string $name
     */
    public function addScore($name, SingleScore $score): void
    {
        $this->getScoreOrCreate($name)->add($score);
        $this->totalScore += $score->getScore();
    }

    /**
     * @param SingleScore[]
     */
    public function addScores(array $scores): void
    {
        foreach ($scores as $name => $singleScores) {
            foreach ($singleScores->getScores() as $score) {
                $this->addScore($name, $score);
            }
        }
    }

    /**
     * @return SingleScore[]
     */
    public function getScores(): array
    {
        return $this->scores;
    }

    public function getScore(string $key): Score
    {
        if (!array_key_exists($key, $this->scores)) {
            throw new \InvalidArgumentException(sprintf('The recommendation does not contains a score named "%s"', $key));
        }

        return $this->scores[$key];
    }

    private function getScoreOrCreate(string $name): Score
    {
        if (!array_key_exists($name, $this->scores)) {
            $this->scores[$name] = new Score($name);
        }

        return $this->scores[$name];
    }

    public function totalScore(): float
    {
        return $this->totalScore;
    }

    public function item(): Node
    {
        return $this->item;
    }
}
