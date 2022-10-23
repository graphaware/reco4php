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

use GraphAware\Reco4PHP\Context\Context;
use Laudis\Neo4j\Types\Node;

class Recommendations
{
    protected Context $context;

    /**
     * @var Recommendation[]
     */
    protected array $recommendations = [];

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getOrCreate(Node $item): Recommendation
    {
        if (array_key_exists($item->getId(), $this->recommendations)) {
            return $this->recommendations[$item->getId()];
        }

        $recommendation = new Recommendation($item);
        $this->recommendations[$item->getId()] = $recommendation;

        return $recommendation;
    }

    public function add(Node $item, string $name, SingleScore $singleScore): void
    {
        $this->getOrCreate($item)->addScore($name, $singleScore);
    }

    public function merge(Recommendations $recommendations): void
    {
        foreach ($recommendations->getItems() as $recommendation) {
            $this->getOrCreate($recommendation->item())->addScores($recommendation->getScores());
        }
    }

    public function remove(Recommendation $recommendation): void
    {
        if (!array_key_exists($recommendation->item()->getId(), $this->recommendations)) {
            return;
        }
        unset($this->recommendations[$recommendation->item()->getId()]);
    }

    /**
     * @return Recommendation[]
     */
    public function getItems(?int $size = null): array
    {
        if (is_int($size) && $size > 0) {
            return array_slice($this->recommendations, 0, $size);
        }

        return array_values($this->recommendations);
    }

    public function get(int $position): Recommendation
    {
        return array_values($this->recommendations)[$position];
    }

    public function size(): int
    {
        return count($this->recommendations);
    }

    public function getItemBy(string $key, mixed $value): ?Recommendation
    {
        foreach ($this->getItems() as $recommendation) {
            if ($recommendation->item()->getProperties()->hasKey($key) && $recommendation->item()->getProperty($key) === $value) {
                return $recommendation;
            }
        }

        return null;
    }

    public function getItemById(int $id): ?Recommendation
    {
        foreach ($this->getItems() as $item) {
            if ($item->item()->getId() === $id) {
                return $item;
            }
        }

        return null;
    }

    public function sort(): void
    {
        usort($this->recommendations, function (Recommendation $recommendationA, Recommendation $recommendationB) {
            return $recommendationA->totalScore() <= $recommendationB->totalScore();
        });
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
