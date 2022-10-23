<?php

namespace GraphAware\Reco4PHP\Tests\Example\PostProcessing;

use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class RewardWellRated extends RecommendationSetPostProcessor
{
    public function buildQuery(Node $input, Recommendations $recommendations): Statement
    {
        $query = 'UNWIND $ids as id
        MATCH (n) WHERE id(n) = id
        MATCH (n)<-[r:RATED]-(u)
        RETURN id(n) as id, sum(r.rating) as score';

        $ids = [];
        foreach ($recommendations->getItems() as $item) {
            $ids[] = $item->item()->getId();
        }

        return Statement::create($query, ['ids' => $ids]);
    }

    public function postProcess(Node $input, Recommendation $recommendation, CypherMap $result): void
    {
        $recommendation->addScore($this->name(), new SingleScore((float) $result->get('score'), 'total_ratings_relationships'));
    }

    public function name(): string
    {
        return 'reward_well_rated';
    }
}
