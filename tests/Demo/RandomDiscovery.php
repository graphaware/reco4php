<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class RandomDiscovery extends SingleDiscoveryEngine
{
    public function query()
    {
        $query = "MATCH (n:User) RETURN n as reco LIMIT 1000";

        return $query;
    }

    public function name()
    {
        return "random";
    }

}