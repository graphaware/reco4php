<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class WatchDiscoveryEngine extends SingleDiscoveryEngine
{
    public function query()
    {
        return "MATCH (input)-[:WATCH]->(repo)<-[:WATCH]-(reco)
        WHERE id(input) = {inputId}
        AND NOT (input)-[:FOLLOWS]->(reco)
        RETURN reco, 1 as score";
    }

    public function name()
    {
        return "user_via_watch";
    }

}