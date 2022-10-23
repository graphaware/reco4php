<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class FromSameGenreILike extends SingleDiscoveryEngine
{
    public function name(): string
    {
        return 'from_genre_i_like';
    }

    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (input) WHERE id(input) = $id
        MATCH (input)-[r:RATED]->(movie)-[:HAS_GENRE]->(genre)
        WITH distinct genre, sum(r.rating) as score
        ORDER BY score DESC
        LIMIT 15
        MATCH (genre)<-[:HAS_GENRE]-(reco)
        RETURN reco
        LIMIT 200';

        return Statement::create($query, ['id' => $input->getId()]);
    }
}
