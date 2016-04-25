<?php

namespace GraphAware\Reco4PHP\Demo\Github;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Result\RecordViewInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\SingleScore;

class SameContribution extends SingleDiscoveryEngine
{
    public function name()
    {
        return 'same_contributions';
    }

    public function discoveryQuery(NodeInterface $input)
    {
        $query = 'MATCH (n) WHERE id(n) = {id}
        MATCH (n)-[:CONTRIBUTED_TO]->(repo)<-[:CONTRIBUTED_TO]-(reco)
        RETURN reco, count(*) as score';

        return Statement::create($query, ['id' => $input->identity()]);
    }

    public function buildScore(NodeInterface $input, NodeInterface $item, RecordViewInterface $record)
    {
        return new SingleScore($record->get('score') * 10);
    }


}