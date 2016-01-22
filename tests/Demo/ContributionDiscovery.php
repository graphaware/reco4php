<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Common\Result\RecordViewInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\SingleScore;

class ContributionDiscovery extends SingleDiscoveryEngine
{
    public function query()
    {
        $query = "MATCH (input)-[r:CONTRIBUTED_TO]->(repo)<-[r2:CONTRIBUTED_TO]-(reco)
        WHERE NOT (input)-[:FOLLOWS]->(reco)
        RETURN reco, sum(r.rate) as inputRate, sum(r2.rate) as outputRate, repo.full_name as reason, size((repo)<-[:WATCH]-()) as score
        LIMIT 200";

        return $query;
    }

    public function buildScore(NodeInterface $input, NodeInterface $item, RecordViewInterface $record)
    {
        $initialScore = $record->value("score");
        $modifier = 100 - abs($record->value("inputRate") - $record->value("outputRate"));
        $contribRate = $initialScore + (($initialScore/100) * $modifier);

        return new SingleScore($contribRate, $record->value("reason"));
    }


    public function name()
    {
        return "user_via_contributions";
    }
}