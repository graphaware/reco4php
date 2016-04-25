<?php

namespace GraphAware\Reco4PHP\Demo\Github;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Result\Record;
use GraphAware\Common\Type\Node;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\Score;
use GraphAware\Reco4PHP\Result\SingleScore;

class PenalizeTooMuchFollowers extends RecommendationSetPostProcessor
{
    public function name()
    {
        return 'too_much_followers';
    }

    public function buildQuery(NodeInterface $input, Recommendations $recommendations)
    {
        $ids = [];
        foreach ($recommendations->getItems() as $recommendation) {
            $ids[] = $recommendation->item()->identity();
        }

        $query = 'UNWIND {ids} as id
        MATCH (n) WHERE id(n) = id
        RETURN id, size((n)<-[:FOLLOWS]-()) as followersCount';

        return Statement::create($query, ['ids' => $ids]);

    }

    public function postProcess(Node $input, Recommendation $recommendation, Record $record)
    {
        $recommendation->addScore($this->name(), new SingleScore(- $record->get('followersCount') / 50));
    }

}