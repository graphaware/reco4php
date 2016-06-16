<?php

namespace GraphAware\Reco4PHP\Tests\Integration\Model;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Filter\BaseBlacklistBuilder;

class SimpleBlacklist extends BaseBlacklistBuilder
{
    public function blacklistQuery(Node $input)
    {
        $query = 'MATCH (n) WHERE n.name = "Zoe" RETURN n as item';

        return Statement::prepare($query, ['id' => $input->identity()]);
    }

    public function name()
    {
        return 'simple_blacklist';
    }

}