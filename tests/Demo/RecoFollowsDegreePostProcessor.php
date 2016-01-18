<?php

namespace GraphAware\Reco4PHP\Tests\Demo;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Score;

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
        $recommendation->addScore(new Score(-($result->getRecord()->value('degree')/10), $this->name()));
    }

    public function name()
    {
        return "penalizeTooMuchFollowers";
    }

}