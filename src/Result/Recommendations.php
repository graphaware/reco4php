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
     * @var \GraphAware\Reco4PHP\Result\Recommendation[]
     */
    protected $recommendations = [];

    /**
     * @param \GraphAware\Common\Type\NodeInterface $item
     *
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
     * @param \GraphAware\Common\Type\NodeInterface   $item
     * @param string                                  $name
     * @param \GraphAware\Reco4PHP\Result\SingleScore $singleScore
     */
    public function add(NodeInterface $item, $name, SingleScore $singleScore)
    {
        $this->getOrCreate($item)->addScore($name, $singleScore);
    }

    /**
     * @param \GraphAware\Reco4PHP\Result\Recommendations $recommendations
     */
    public function merge(Recommendations $recommendations)
    {
        foreach ($recommendations->getItems() as $recommendation) {
            $this->getOrCreate($recommendation->item())->addScores($recommendation->getScores());
        }
    }

    public function remove(Recommendation $recommendation)
    {
        if (!array_key_exists($recommendation->item()->identity(), $this->recommendations)) {
            return;
        }
        unset($this->recommendations[$recommendation->item()->identity()]);
    }

    /**
     * @return \GraphAware\Reco4PHP\Result\Recommendation[]
     */
    public function getItems($size = null)
    {
        if (is_int($size) && $size > 0) {
            return array_slice($this->recommendations, 0, $size);
        }

        return array_values($this->recommendations);
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->recommendations);
    }

    public function sort()
    {
        usort($this->recommendations, function (Recommendation $recommendationA, Recommendation $recommendationB) {
            return $recommendationA->totalScore() <= $recommendationB->totalScore();
        });
    }
}
