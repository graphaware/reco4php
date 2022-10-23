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

use Laudis\Neo4j\Types\Node;

interface Filter
{
    /**
     * Returns whether or not the recommended node should be included in the recommendation.
     */
    public function doInclude(Node $input, Node $item): bool;
}
