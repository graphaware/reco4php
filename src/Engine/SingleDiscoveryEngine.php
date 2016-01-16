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

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Transactional\BaseCypherAware;

abstract class SingleDiscoveryEngine extends BaseCypherAware implements DiscoveryEngine
{
    public function idParamName()
    {
        return "inputId";
    }

    public function recoResultName()
    {
        return "reco";
    }

    public function scoreResultName()
    {
        return "score";
    }

    final public function buildParams(NodeInterface $input)
    {
        $this->addParameter($this->idParamName(), $input->identity());
    }


}