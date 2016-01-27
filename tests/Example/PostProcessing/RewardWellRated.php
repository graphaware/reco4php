<?php

namespace GraphAware\Reco4PHP\Tests\Example\PostProcessing;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\SingleScore;

class RewardWellRated implements CypherAwarePostProcessor
{
    public function buildQuery(NodeInterface $input, Recommendation $recommendation)
    {
        $query = 'MATCH (item) WHERE id(item) = {itemId}
        RETURN size((item)<-[:RATED]-()) as ratings';

        return Statement::create($query, ['itemId' => $recommendation->item()->identity()]);
    }

    public function postProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result = null)
    {
        $record = $result->getRecord();
        if ($rating = $record->value("ratings")) {
            if ($rating > 10) {
                $recommendation->addScore($this->name(), new SingleScore($rating));
            }
        }
    }

    public function name()
    {
        return "reward_well_rated";
    }

}