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

use Laudis\Neo4j\Types\CypherList;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

abstract class BaseBlacklistBuilder implements BlackListBuilder
{
    /**
     * @return Node[]
     */
    public function buildBlackList(CypherList $results): array
    {
        $nodes = [];
        /** @var CypherMap $result */
        foreach ($results as $result) {
            if ($result->hasKey($this->itemResultName()) && $result->get($this->itemResultName()) instanceof Node) {
                $nodes[] = $result->get($this->itemResultName());
            }
        }

        return $nodes;
    }

    public function itemResultName(): string
    {
        return 'item';
    }
}
