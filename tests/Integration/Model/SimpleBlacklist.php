<?php

namespace GraphAware\Reco4PHP\Tests\Integration\Model;

use GraphAware\Reco4PHP\Filter\BaseBlacklistBuilder;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class SimpleBlacklist extends BaseBlacklistBuilder
{
    public function blacklistQuery(Node $input): Statement
    {
        $query = 'MATCH (n) WHERE n.name = "Zoe" RETURN n as item';

        return Statement::create($query);
    }

    public function name(): string
    {
        return 'simple_blacklist';
    }
}
