<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Score;

class LanguagePostProcessor extends CypherAwarePostProcessor
{
    public function query()
    {
        $query = "MATCH (input)-[:LIKE_LANGUAGE]->(l)<-[:LIKE_LANGUAGE]-(reco)
        RETURN id(l) as bool";

        return $query;
    }

    public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result)
    {
        if (count($result->records()) === 0 || !$result->getRecord()->hasValues()) {
            return;
        }

        $recommendation->addScore(new Score(10, $this->name()));
    }

    public function name()
    {
        return "rewardLikeSameLanguage";
    }

}