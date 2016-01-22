<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\PartialScore;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Score;

class LanguagePostProcessor extends CypherAwarePostProcessor
{
    public function query()
    {
        $query = "MATCH (reco)-[:LIKE_LANGUAGE]->(l:Language {name:'PHP'}) RETURN id(l) as bool";

        return $query;
    }

    public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result)
    {
        if (count($result->records()) === 0 || !$result->getRecord()->hasValues()) {
            return;
        }

        $recommendation->add(new PartialScore(1, $this->name()));
    }

    public function name()
    {
        return "rewardLikeSameLanguage";
    }

}