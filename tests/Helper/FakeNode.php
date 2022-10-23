<?php

namespace GraphAware\Reco4PHP\Tests\Helper;

use Laudis\Neo4j\Types\CypherList;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class FakeNode
{
    public static function createDummy(?int $id = null)
    {
        $id = $id ?? rand(0, 1000);

        return new Node($id, new CypherList(['Dummy']), new CypherMap([]));
    }
}
