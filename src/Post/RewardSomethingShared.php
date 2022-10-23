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

use GraphAware\Reco4PHP\Graph\Direction;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherList;
use Laudis\Neo4j\Types\Node;

abstract class RewardSomethingShared implements CypherAwarePostProcessor
{
    abstract public function relationshipType();

    public function relationshipDirection(): string
    {
        return Direction::BOTH;
    }

    final public function buildQuery(Node $input, Recommendation $recommendation): Statement
    {
        $relationshipPatterns = [
            Direction::BOTH => ['-[:%s]-', '-[:%s]-'],
            Direction::INCOMING => ['<-[:%s]-', '-[:%s]->'],
            Direction::OUTGOING => ['-[:%s]->', '<-[:%s]-'],
        ];

        $relPattern = sprintf($relationshipPatterns[$this->relationshipDirection()][0], $this->relationshipType());
        $inversedRelPattern = sprintf($relationshipPatterns[$this->relationshipDirection()][1], $this->relationshipType());

        $query = 'MATCH (input) WHERE id(input) = $inputId, (item) WHERE id(item) = $itemId
        MATCH (input)'.$relPattern.'(shared)'.$inversedRelPattern.'(item)
        RETURN shared as sharedThing';

        return Statement::create($query, ['inputId' => $input->getId(), 'itemId' => $recommendation->item()->getId()]);
    }

    public function postProcess(Node $input, Recommendation $recommendation, ?CypherList $results = null): void
    {
        if (null === $results) {
            throw new \RuntimeException(sprintf('Expected a non-null result in %s::postProcess()', get_class($this)));
        }

        if (count($results) > 0) {
            foreach ($results as $result) {
                $recommendation->addScore($this->name(), new SingleScore(1));
            }
        }
    }
}
