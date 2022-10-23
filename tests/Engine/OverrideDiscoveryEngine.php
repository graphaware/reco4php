<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Tests\Engine;

use GraphAware\Reco4PHP\Context\Context;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class OverrideDiscoveryEngine extends TestDiscoveryEngine
{
    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (n) WHERE id(n) <> $input
        RETURN n LIMIT $limit';

        return Statement::create($query, ['input' => $input->getId(), 'limit' => 300]);
    }

    public function idParamName(): string
    {
        return 'source';
    }

    public function recoResultName(): string
    {
        return 'recommendation';
    }

    public function scoreResultName(): string
    {
        return 'rate';
    }

    public function defaultScore(): float
    {
        return 10;
    }
}
