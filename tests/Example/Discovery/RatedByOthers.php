<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class RatedByOthers extends SingleDiscoveryEngine
{
    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (input:User) WHERE id(input) = $id
        MATCH (input)-[:RATED]->(m)<-[:RATED]-(o)
        WITH distinct o
        MATCH (o)-[:RATED]->(reco)
        RETURN distinct reco LIMIT 500';

        return Statement::create($query, ['id' => $input->getId()]);
    }

    public function name(): string
    {
        return 'rated_by_others';
    }
}
