<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Filter\BaseBlackListBuilder;

class AlreadyRatedBlackList extends BaseBlackListBuilder
{
    public function blacklistQuery(NodeInterface $input)
    {
        $query = 'MATCH (input) WHERE id(input) = {inputId}
        MATCH (input)-[:RATED]->(movie)
        RETURN movie as item';

        return Statement::create($query, ['inputId' => $input->identity()]);
    }

    public function name()
    {
        return 'already_rated';
    }
}