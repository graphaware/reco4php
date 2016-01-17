<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class WatchDiscoveryEngine extends SingleDiscoveryEngine
{
    public function query()
    {
        return "MATCH (input)-[:WATCH]->(repo)<-[:WATCH]-(reco)
        WHERE NOT (input)-[:FOLLOWS]->(reco)
        RETURN reco, count(*) as score
        ORDER BY score DESC
        LIMIT 500";
    }

    public function name()
    {
        return "user_via_watch";
    }

    public function defaultScore()
    {
        return 10;
    }
}