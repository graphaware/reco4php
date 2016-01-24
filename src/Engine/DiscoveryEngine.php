<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Engine;

use GraphAware\Common\Result\RecordViewInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Transactional\CypherAware;
use GraphAware\Common\Result\ResultCollection;

interface DiscoveryEngine extends CypherAware
{
    public function name();

    public function buildScore(NodeInterface $input, NodeInterface $item, RecordViewInterface $record);

    public function produceRecommendations(NodeInterface $input, ResultCollection $resultCollection);

    public function idParamName();

    public function recoResultName();

    public function scoreResultName();

    public function defaultScore();

    public function buildParams(NodeInterface $input);
}
