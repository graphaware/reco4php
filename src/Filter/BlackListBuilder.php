<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Filter;

use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherList;
use Laudis\Neo4j\Types\Node;

interface BlackListBuilder
{
    public function blacklistQuery(Node $input): Statement;

    /**
     * @param CypherList
     *
     * @return Node[]
     */
    public function buildBlackList(CypherList $results): array;

    public function itemResultName(): string;

    public function name(): string;
}
