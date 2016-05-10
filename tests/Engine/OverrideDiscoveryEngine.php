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

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Result\RecordViewInterface;
use GraphAware\Common\Type\Node;

class OverrideDiscoveryEngine extends TestDiscoveryEngine
{
    public function discoveryQuery(Node $input)
    {
        $query = "MATCH (n) WHERE id(n) <> {input}
        RETURN n LIMIT {limit}";

        return Statement::create($query, ['input' => $input->identity(), 'limit' => 300]);
    }

    public function buildScore(Node $input, Node $item, RecordViewInterface $record)
    {
        return parent::buildScore($input, $item, $record);
    }

    public function idParamName()
    {
        return "source";
    }

    public function recoResultName()
    {
       return "recommendation";
    }

    public function scoreResultName()
    {
        return "rate";
    }

    public function defaultScore()
    {
        return 10;
    }

}