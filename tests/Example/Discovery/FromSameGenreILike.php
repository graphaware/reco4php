<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class FromSameGenreILike extends SingleDiscoveryEngine
{
    public function name()
    {
        return 'from_genre_i_like';
    }

    public function discoveryQuery(NodeInterface $input)
    {
        $query = 'MATCH (input) WHERE id(input) = {id}
        MATCH (input)-[r:RATED]->(movie)-[:HAS_GENRE]->(genre)
        WITH distinct genre, sum(r.rating) as score, input
        ORDER BY score DESC
        LIMIT 15
        MATCH (genre)<-[:HAS_GENRE]-(reco)
        WHERE NOT (input)-[:RATED]->(reco)
        RETURN reco
        LIMIT 200';

        return Statement::create($query, ['id' => $input->identity()]);
    }

}