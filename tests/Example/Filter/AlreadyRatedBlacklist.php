<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Reco4PHP\Filter\BaseBlackListBuilder;

class AlreadyRatedBlackList extends BaseBlackListBuilder
{
    public function query()
    {
        $query = "MATCH (input)-[:RATED]->(movie)
        RETURN movie as item";

        return $query;
    }

}