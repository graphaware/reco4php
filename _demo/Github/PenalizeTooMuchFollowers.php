<?php

namespace GraphAware\Reco4PHP\Demo\Github;

use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class PenalizeTooMuchFollowers extends RecommendationSetPostProcessor
{
    public function name(): string
    {
        return 'too_much_followers';
    }

    public function buildQuery(Node $input, Recommendations $recommendations): Statement
    {
        $ids = [];
        foreach ($recommendations->getItems() as $recommendation) {
            $ids[] = $recommendation->item()->getId();
        }

        $query = 'UNWIND $ids as id
        MATCH (n) WHERE id(n) = id
        RETURN id, size((n)<-[:FOLLOWS]-()) as followersCount';

        return Statement::create($query, ['ids' => $ids]);
    }

    public function postProcess(Node $input, Recommendation $recommendation, CypherMap $result): void
    {
        $recommendation->addScore($this->name(), new SingleScore(-(int) $result->get('followersCount') / 50));
    }
}
