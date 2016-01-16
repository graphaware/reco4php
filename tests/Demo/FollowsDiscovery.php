<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class FollowsDiscovery extends SingleDiscoveryEngine
{
    public function query()
    {
        return "MATCH (input)-[:FOLLOWS]->(friend)-[:FOLLOWS]->(fof)
        RETURN fof as reco, count(*) as score
        ORDER BY score DESC
        LIMIT 500";
    }

    public function name()
    {
        return "follows_discovery";
    }

}