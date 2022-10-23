<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Reco4PHP\Filter\BaseBlacklistBuilder;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class AlreadyRatedBlackList extends BaseBlacklistBuilder
{
    public function blacklistQuery(Node $input): Statement
    {
        $query = 'MATCH (input) WHERE id(input) = $inputId
        MATCH (input)-[:RATED]->(movie)
        RETURN movie as item';

        return Statement::create($query, ['inputId' => $input->getId()]);
    }

    public function name(): string
    {
        return 'already_rated';
    }
}
