<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class RatedByOthers extends SingleDiscoveryEngine
{
    public function query()
    {
        $query = "MATCH (input:User)-[:RATED]->(m)
WHERE 10 < size( (m)<-[:RATED]-() ) < 100
MATCH (m)<-[:RATED]-(other)
WITH distinct other
MATCH (other)-[r:RATED]->(reco)
WITH reco, sum(r.rating) as score
ORDER BY score DESC
RETURN reco, score LIMIT 100";

        return $query;
    }

    public function name()
    {
        return "rated_by_others";
    }

}