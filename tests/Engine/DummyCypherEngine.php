<?php

namespace GraphAware\Reco4PHP\Tests\Engine;

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\CypherEngine;

class DummyCypherEngine extends CypherEngine
{
    public function doRecommendSingle(NodeInterface $input)
    {
        $query = "MATCH (input)-[:FOLLOWS]->(user)-[:RATED]->(movie)
        WHERE NOT (input)-[:RATED]->(movie)
        RETURN movie as reco, count(*) as score";

        return $query;
    }

    public function name()
    {
        return "dummy-engine";
    }

}