<?php

namespace GraphAware\Reco4PHP\Demo\Github;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class FollowedByFollowers extends SingleDiscoveryEngine
{
    public function name(): string
    {
        return 'followed_by_followers';
    }

    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (input) WHERE id(input) = $id
        MATCH (input)<-[:FOLLOWS]-(follower)-[:FOLLOWS]->(reco)
        WHERE size((follower)-[:FOLLOWS]->()) < $max_follows
        RETURN reco, count(*) as score
        LIMIT 100';

        return Statement::create($query, ['id' => $input->getId(), 'max_follows' => 200]);
    }

    public function buildScore(Node $input, Node $item, CypherMap $result, Context $context): SingleScore
    {
        return new SingleScore((float) $result->get('score'));
    }
}
