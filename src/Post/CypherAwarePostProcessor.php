<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Post;

use GraphAware\Reco4PHP\Result\Recommendation;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

interface CypherAwarePostProcessor extends PostProcessor
{
    /**
     * @return Statement the statement to be executed
     */
    public function buildQuery(Node $input, Recommendation $recommendation): Statement;
}
