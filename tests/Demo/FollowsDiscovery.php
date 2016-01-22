<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class FollowsDiscovery extends SingleDiscoveryEngine
{
    public function query()
    {
        $query = "MATCH (input)-[:FOLLOWS]->(user)<-[:FOLLOWS]-(reco)
        WHERE NOT (input)-[:FOLLOWS]->(reco)
        RETURN reco, user.login as reason
        LIMIT 200";

        return $query;
    }

    public function name()
    {
        return "follows_discovery";
    }

}