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

use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Result\Recommendation;

interface CypherAwarePostProcessor extends PostProcessor
{
    /**
     * @param \GraphAware\Common\Type\Node               $input
     * @param \GraphAware\Reco4PHP\Result\Recommendation $recommendation
     *
     * @return \GraphAware\Common\Cypher\Statement the statement to be executed
     */
    public function buildQuery(Node $input, Recommendation $recommendation);
}
