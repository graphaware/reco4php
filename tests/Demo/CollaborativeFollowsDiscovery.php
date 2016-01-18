<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class CollaborativeFollowsDiscovery extends SingleDiscoveryEngine
{
    public function query()
    {
        $query = "MATCH (input)-[:FOLLOWS]->(user)<-[:FOLLOWS]-(otherUser)
        WHERE size((otherUser)-[:FOLLOWS]-()) < 100
WITH distinct otherUser, count(*) as x
ORDER BY x DESC
LIMIT 10
MATCH (otherUser)-[:FOLLOWS]->(reco)
WHERE size((reco)<-[:FOLLOWS]-()) < 100
RETURN reco, count(*) as c
ORDER BY c DESC
LIMIT 10";

        return $query;
    }

    public function name()
    {
        return "collaborative_follows";
    }

}