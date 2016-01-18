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

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Transactional\CypherAware;

interface BlackListBuilder extends CypherAware
{
    /**
     * @param \GraphAware\Common\Type\NodeInterface $input
     *
     * @return \GraphAware\Common\Type\NodeInterface[]
     */
    public function buildBlackList(NodeInterface $input);
}