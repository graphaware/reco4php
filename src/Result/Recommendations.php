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
use GraphAware\Reco4PHP\Context\Context;

class Recommendations
{
    /**
     * @var \GraphAware\Reco4PHP\Context\Context
     */
    protected $context;

    /**
     * @var \GraphAware\Reco4PHP\Result\Recommendation[]
     */
    protected $recommendations = [];

    /**
     * @param \GraphAware\Reco4PHP\Context\Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param \GraphAware\Common\Type\Node $item
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendation
     */
    public function getOrCreate(Node $item)
    {
        if (array_key_exists($item->identity(), $this->recommendations)) {
            return $this->recommendations[$item->identity()];
        }

        $recommendation = new Recommendation($item);
        $this->recommendations[$item->identity()] = $recommendation;

        return $recommendation;
    }

    /**
     * @param \GraphAware\Common\Type\Node            $item
     * @param string                                  $name
     * @param \GraphAware\Reco4PHP\Result\SingleScore $singleScore
     */
    public function add(Node $item, $name, SingleScore $singleScore)
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
    public function getItems($size = null) : array
    {
        if (is_int($size) && $size > 0) {
            return array_slice($this->recommendations, 0, $size);
        }

        return array_values($this->recommendations);
    }

    /**
     * @param $position
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendation
     */
    public function get($position) : Recommendation
    {
        return array_values($this->recommendations)[$position];
    }

    /**
     * @return int
     */
    public function size() : int
    {
        return count($this->recommendations);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return \GraphAware\Reco4PHP\Result\Recommendation|null
     */
    public function getItemBy($key, $value)
    {
        foreach ($this->getItems() as $recommendation) {
            if ($recommendation->item()->hasValue($key) && $recommendation->item()->value($key) === $value) {
                return $recommendation;
            }
        }

        return null;
    }

    /**
     * @param int $id
     * @return \GraphAware\Reco4PHP\Result\Recommendation|null
     */
    public function getItemById($id)
    {
        foreach ($this->getItems() as $item) {
            if ($item->item()->identity() === $id) {
                return $item;
            }
        }

        return null;
    }

    public function sort()
    {
        usort($this->recommendations, function (Recommendation $recommendationA, Recommendation $recommendationB) {
            return $recommendationA->totalScore() <= $recommendationB->totalScore();
        });
    }

    /**
     * @return \GraphAware\Reco4PHP\Context\Context
     */
    public function getContext() : Context
    {
        return $this->context;
    }
}
