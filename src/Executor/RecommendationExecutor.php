<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Executor;

use GraphAware\Common\Result\ResultCollection;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use Symfony\Component\Stopwatch\Stopwatch;

class RecommendationExecutor
{
    /**
     * @var \GraphAware\Reco4PHP\Executor\DiscoveryPhaseExecutor
     */
    protected $discoveryExecutor;

    /**
     * @var \GraphAware\Reco4PHP\Executor\PostProcessPhaseExecutor
     */
    protected $postProcessExecutor;

    public function __construct(DatabaseService $databaseService)
    {
        $this->discoveryExecutor = new DiscoveryPhaseExecutor($databaseService);
        $this->postProcessExecutor = new PostProcessPhaseExecutor($databaseService);
        $this->stopwatch = new Stopwatch();
    }

    public function processRecommendation(Node $input, RecommendationEngine $engine, Context $context)
    {
        $recommendations = $this->doDiscovery($input, $engine, $context);
        $this->doPostProcess($input, $recommendations, $engine);
        $recommendations->sort();

        return $recommendations;
    }

    private function doDiscovery(Node $input, RecommendationEngine $engine, Context $context)
    {
        $recommendations = new Recommendations();
        $result = $this->discoveryExecutor->processDiscovery(
            $input,
            $engine->getDiscoveryEngines(),
            $engine->getBlacklistBuilders(),
            $context
        );

        foreach ($engine->getDiscoveryEngines() as $discoveryEngine) {
            $recommendations->merge($discoveryEngine->produceRecommendations($input, $result, $context));
        }

        $blacklist = $this->buildBlacklistedNodes($result, $engine);
        $this->removeIrrelevant($input, $engine, $recommendations, $blacklist);

        return $recommendations;
    }

    private function doPostProcess(Node $input, Recommendations $recommendations, RecommendationEngine $engine)
    {
        $postProcessResult = $this->postProcessExecutor->execute($input, $recommendations, $engine);
        foreach ($engine->getPostProcessors() as $postProcessor) {
            $tag = $postProcessor->name();
            $result = $postProcessResult->get($tag);
            $postProcessor->handleResultSet($input, $result, $recommendations);
        }
    }

    public function removeIrrelevant(Node $input, RecommendationEngine $engine, Recommendations $recommendations, array $blacklist)
    {
        foreach ($recommendations->getItems() as $recommendation) {
            foreach ($engine->filters() as $filter) {
                if (!$filter->doInclude($input, $recommendation->item()) || array_key_exists($recommendation->item()->identity(), $blacklist)) {
                    $recommendations->remove($recommendation);
                }
            }
        }
    }

    public function buildBlacklistedNodes(ResultCollection $result, RecommendationEngine $engine)
    {
        $set = [];
        foreach ($engine->getBlacklistBuilders() as $blacklist) {
            $res = $result->get($blacklist->name());
            foreach ($res->records() as $record) {
                if ($record->hasValue($blacklist->itemResultName())) {
                    $node = $record->get($blacklist->itemResultName());
                    if ($node instanceof Node) {
                        $set[$node->identity()] = $node;
                    }
                }
            }
        }

        return $set;
    }
}
