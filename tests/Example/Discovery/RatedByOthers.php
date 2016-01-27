<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class RatedByOthers extends SingleDiscoveryEngine
{
    public function discoveryQuery(NodeInterface $input)
    {
        $query = 'MATCH (input:User) WHERE id(input) = {id}
        MATCH (input)-[:RATED]->(movie)<-[:RATED]-(other)
        WITH distinct other
        MATCH (other)-[:RATED]->(reco)
        RETURN reco, count(*) as score
        ORDER BY score DESC
        LIMIT 200';

        return Statement::create($query, ['id' => $input->identity()]);
    }


    public function name()
    {
        return "rated_by_others";
    }

}