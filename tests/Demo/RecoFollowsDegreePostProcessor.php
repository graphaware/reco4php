<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\PartialScore;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Score;
use GraphAware\Reco4PHP\Result\SingleScore;

class RecoFollowsDegreePostProcessor extends CypherAwarePostProcessor
{
    public function query()
    {
        $query = "RETURN size((reco)<-[:FOLLOWS]-()) as degree";

        return $query;
    }

    public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result)
    {
        if ($result->getRecord()->value('degree') <= 0) {
            return;
        }

        $recommendation->addScore($this->name(), new SingleScore(-($result->getRecord()->value('degree') / 100)));
    }

    public function name()
    {
        return "penalizeTooMuchFollowers";
    }

}