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

class Recommendations
{
    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var \GraphAware\Reco4PHP\Result\Recommendation[]
     */
    protected $recommendations = [];

    /**
     * @param \GraphAware\Common\Type\NodeInterface $item
     * @return \GraphAware\Reco4PHP\Result\Recommendation
     */
    public function getOrCreate(NodeInterface $item)
    {
        if (array_key_exists($item->identity(), $this->recommendations)) {
            return $this->recommendations[$item->identity()];
        }

        $recommendation = new Recommendation($item);
        $this->recommendations[$item->identity()] = $recommendation;

        return $recommendation;
    }

    /**
     * @param \GraphAware\Common\Type\NodeInterface $item
     * @param \GraphAware\Reco4PHP\Result\Score|null $score
     */
    public function add(NodeInterface $item, Score $score = null)
    {
        $this->getOrCreate($item)->addScore($score);
    }

    /**
     * @return \GraphAware\Reco4PHP\Result\Recommendation[]
     */
    public function getItems()
    {
        return array_values($this->recommendations);
    }

    /**
     * @return int
     */
    public function size()
    {
        return $this->count();
    }

    /**
     * @return \GraphAware\Reco4PHP\Result\Recommendation
     */
    public function current()
    {
        return $this->recommendations[$this->position];
    }

    /**
     * @void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->recommendations[$this->position]);
    }

    /**
     * @void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->recommendations);
    }

    public function sort()
    {
        usort($this->recommendations, function(Recommendation $recommendationA, Recommendation $recommendationB){
            return $recommendationA->totalScore() <= $recommendationB->totalScore();
        });
    }

}