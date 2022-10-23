<?php

namespace GraphAware\Reco4PHP\Tests\Integration\Model;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class FriendsEngine extends SingleDiscoveryEngine
{
    public function name(): string
    {
        return 'friends_discovery';
    }

    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (n) WHERE id(n) = $id
        MATCH (n)-[:FRIEND]->(friend)-[:FRIEND]->(reco)
        WHERE NOT (n)-[:FRIEND]->(reco)
        RETURN reco, count(*) as score';

        return Statement::create($query, ['id' => $input->getId()]);
    }
}
