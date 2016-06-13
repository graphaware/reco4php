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
use GraphAware\Common\Cypher\StatementInterface;
use GraphAware\Common\Result\Record;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Result\SingleScore;

class OverrideDiscoveryEngine extends TestDiscoveryEngine
{
    public function discoveryQuery(Node $input, Context $context) : StatementInterface
    {
        $query = "MATCH (n) WHERE id(n) <> {input}
        RETURN n LIMIT {limit}";

        return Statement::create($query, ['input' => $input->identity(), 'limit' => 300]);
    }

    public function buildScore(Node $input, Node $item, Record $record, Context $context) : SingleScore
    {
        return parent::buildScore($input, $item, $record, $context);
    }

    public function idParamName() : string
    {
        return "source";
    }

    public function recoResultName() : string
    {
       return "recommendation";
    }

    public function scoreResultName() : string
    {
        return "rate";
    }

    public function defaultScore() : float
    {
        return 10;
    }

}