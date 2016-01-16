<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Common\Result\RecordViewInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\Score;

class FollowsDiscovery extends SingleDiscoveryEngine
{
    public function query()
    {
        return "MATCH (input)-[:FOLLOWS]->(friend)-[:FOLLOWS]->(fof)
        WHERE NOT (input)-[:FOLLOWS]->(fof)
        RETURN fof as reco, count(friend) as score
        ORDER BY score DESC
        LIMIT 500";
    }

    public function name()
    {
        return "follows_discovery";
    }

}