<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Post;

use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Recommendations;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherList;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

abstract class RecommendationSetPostProcessor implements PostProcessor
{
    abstract public function buildQuery(Node $input, Recommendations $recommendations): Statement;

    abstract public function postProcess(Node $input, Recommendation $recommendation, CypherMap $result): void;

    final public function handleResultSet(Node $input, CypherList $results, Recommendations $recommendations): void
    {
        $resultMap = [];
        /** @var CypherMap $result */
        foreach ($results as $result) {
            if (!$result->hasKey($this->idResultName())) {
                throw new \InvalidArgumentException(sprintf('The record does not contain a value with key "%s" in "%s"', $this->idResultName(), $this->name()));
            }
            $resultMap[$result->get($this->idResultName())] = $result;
        }

        foreach ($recommendations->getItems() as $recommendation) {
            if (array_key_exists($recommendation->item()->getId(), $resultMap)) {
                $this->postProcess($input, $recommendation, $resultMap[$recommendation->item()->getId()]);
            }
        }
    }

    public function idResultName(): string
    {
        return 'id';
    }
}
