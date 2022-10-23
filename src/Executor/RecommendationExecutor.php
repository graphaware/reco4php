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

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\ResultCollection;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class RecommendationExecutor
{
    protected DiscoveryPhaseExecutor $discoveryExecutor;

    protected PostProcessPhaseExecutor $postProcessExecutor;

    public function __construct(DatabaseService $databaseService)
    {
        $this->discoveryExecutor = new DiscoveryPhaseExecutor($databaseService);
        $this->postProcessExecutor = new PostProcessPhaseExecutor($databaseService);
    }

    public function processRecommendation(Node $input, RecommendationEngine $engine, Context $context): Recommendations
    {
        $recommendations = $this->doDiscovery($input, $engine, $context);
        $this->doPostProcess($input, $recommendations, $engine);
        $recommendations->sort();

        return $recommendations;
    }

    private function doDiscovery(Node $input, RecommendationEngine $engine, Context $context): Recommendations
    {
        $recommendations = new Recommendations($context);
        $context->getStatistics()->startDiscovery();
        $result = $this->discoveryExecutor->processDiscovery(
            $input,
            $engine->getDiscoveryEngines(),
            $engine->getBlacklistBuilders(),
            $context
        );

        foreach ($engine->getDiscoveryEngines() as $discoveryEngine) {
            $recommendations->merge($discoveryEngine->produceRecommendations($input, $result, $context));
        }
        $context->getStatistics()->stopDiscovery();

        $blacklist = $this->buildBlacklistedNodes($result, $engine);
        $this->removeIrrelevant($input, $engine, $recommendations, $blacklist);

        return $recommendations;
    }

    private function doPostProcess(Node $input, Recommendations $recommendations, RecommendationEngine $engine): void
    {
        $recommendations->getContext()->getStatistics()->startPostProcess();
        $postProcessResult = $this->postProcessExecutor->execute($input, $recommendations, $engine);
        foreach ($engine->getPostProcessors() as $postProcessor) {
            $tag = $postProcessor->name();
            $results = $postProcessResult->get($tag);
            if ($postProcessor instanceof RecommendationSetPostProcessor) {
                $postProcessor->handleResultSet($input, $results, $recommendations);
            }
        }
        $recommendations->getContext()->getStatistics()->stopPostProcess();
    }

    private function removeIrrelevant(Node $input, RecommendationEngine $engine, Recommendations $recommendations, array $blacklist): void
    {
        foreach ($recommendations->getItems() as $recommendation) {
            if (array_key_exists($recommendation->item()->getId(), $blacklist)) {
                $recommendations->remove($recommendation);
            } else {
                foreach ($engine->filters() as $filter) {
                    if (!$filter->doInclude($input, $recommendation->item())) {
                        $recommendations->remove($recommendation);
                        break;
                    }
                }
            }
        }
    }

    private function buildBlacklistedNodes(ResultCollection $resultCollection, RecommendationEngine $engine): array
    {
        $set = [];
        foreach ($engine->getBlacklistBuilders() as $blacklist) {
            $results = $resultCollection->get($blacklist->name());
            /** @var CypherMap $result */
            foreach ($results as $result) {
                if ($result->hasKey($blacklist->itemResultName())) {
                    $node = $result->get($blacklist->itemResultName());
                    if ($node instanceof Node) {
                        $set[$node->getId()] = $node;
                    }
                }
            }
        }

        return $set;
    }
}
