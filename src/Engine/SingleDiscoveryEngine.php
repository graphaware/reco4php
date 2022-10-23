<?php

declare(strict_types=1);

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Engine;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\ResultCollection;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

abstract class SingleDiscoveryEngine implements DiscoveryEngine
{
    private static $DEFAULT_RECO_NAME = 'reco';
    private static $DEFAULT_SCORE_NAME = 'score';
    private static $DEFAULT_REASON_NAME = 'reason';

    /**
     * {@inheritdoc}
     */
    public function buildScore(Node $input, Node $item, CypherMap $result, Context $context): SingleScore
    {
        $score = $result->hasKey($this->scoreResultName()) ? $result->get($this->scoreResultName()) : $this->defaultScore();
        $reason = $result->hasKey($this->reasonResultName()) ? $result->get($this->reasonResultName()) : null;

        return new SingleScore($score, $reason);
    }

    /**
     * {@inheritdoc}
     */
    final public function produceRecommendations(Node $input, ResultCollection $resultCollection, Context $context): Recommendations
    {
        $results = $resultCollection->get($this->name());
        $recommendations = new Recommendations($context);

        /** @var CypherMap $result */
        foreach ($results as $result) {
            if ($result->hasKey($this->recoResultName())) {
                /** @var Node $node */
                $node = $result->get($this->recoResultName());
                $recommendations->add($node, $this->name(), $this->buildScore($input, $node, $result, $context));
            }
        }

        return $recommendations;
    }

    /**
     * {@inheritdoc}
     */
    public function recoResultName(): string
    {
        return self::$DEFAULT_RECO_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function scoreResultName(): string
    {
        return self::$DEFAULT_SCORE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function reasonResultName(): string
    {
        return self::$DEFAULT_REASON_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultScore(): float
    {
        return 1.0;
    }
}
