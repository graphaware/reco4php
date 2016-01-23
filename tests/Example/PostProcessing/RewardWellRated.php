<?php

namespace GraphAware\Reco4PHP\Tests\Example\PostProcessing;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\SingleScore;

class RewardWellRated extends CypherAwarePostProcessor
{
    public function query()
    {
        $query = "RETURN size((reco)<-[:RATED]-()) as ratings";

        return $query;
    }

    public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result)
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