<?php

namespace GraphAware\Reco4PHP\Demo\Github;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class SameContribution extends SingleDiscoveryEngine
{
    public function name(): string
    {
        return 'same_contributions';
    }

    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (n) WHERE id(n) = $id
        MATCH (n)-[:CONTRIBUTED_TO]->(repo)<-[:CONTRIBUTED_TO]-(reco)
        RETURN reco, count(*) as score';

        return Statement::create($query, ['id' => $input->getId()]);
    }

    public function buildScore(Node $input, Node $item, CypherMap $result, Context $context): SingleScore
    {
        return new SingleScore((float) $result->get('score') * 10);
    }
}
