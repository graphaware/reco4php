<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class WatchDiscoveryEngine extends SingleDiscoveryEngine
{
    public function query()
    {
        return "MATCH (input)-[:WATCH]->(repo)<-[:WATCH]-(reco)
        WHERE NOT (input)-[:FOLLOWS]->(reco)
        RETURN reco, repo.full_name as reason, count(*) as c
        ORDER BY c DESC
        LIMIT 100";
    }

    public function name()
    {
        return "user_via_watch";
    }

    public function defaultScore()
    {
        return 1;
    }
}