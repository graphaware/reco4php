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
MATCH p=(input)-[r:RATED]->(movie)<-[r2:RATED]-(other)
WITH other, input, collect(p) as paths
WITH other, input, reduce(x=0, p in paths | x + reduce(i=0, r in rels(p) | i+r.rating)) as score
WITH other, input, score
ORDER BY score DESC
MATCH (other)-[:RATED]->(reco)
WHERE NOT (input)-[:RATED]->(reco)
RETURN reco
LIMIT 500';

        return Statement::create($query, ['id' => $input->identity()]);
    }


    public function name()
    {
        return "rated_by_others";
    }

}