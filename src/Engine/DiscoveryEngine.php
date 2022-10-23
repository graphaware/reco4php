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

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\ResultCollection;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

interface DiscoveryEngine
{
    /**
     * @return string The name of the discovery engine
     */
    public function name(): string;

    /**
     * The statement to be executed for finding items to be recommended.
     */
    public function discoveryQuery(Node $input, Context $context): Statement;

    /**
     * Returns the score produced by the recommended item.
     *
     * @return SingleScore A single score produced for the recommended item
     */
    public function buildScore(Node $input, Node $item, CypherMap $result, Context $context): SingleScore;

    /**
     * Returns a collection of Recommendation object produced by this discovery engine.
     */
    public function produceRecommendations(Node $input, ResultCollection $resultCollection, Context $context): Recommendations;

    /**
     * @return string The column identifier of the row result representing the recommended item (node)
     */
    public function recoResultName(): string;

    /**
     * @return string The column identifier of the row result representing the score to be used, note that this
     *                is not mandatory to have a score in the result. If empty, the score will be the float value returned by
     *                <code>defaultScore()</code> or the score logic if the concrete class override the <code>buildScore</code>
     *                method.
     */
    public function scoreResultName(): string;

    /**
     * @return float The default score to be given to the discovered recommended item
     */
    public function defaultScore(): float;
}
