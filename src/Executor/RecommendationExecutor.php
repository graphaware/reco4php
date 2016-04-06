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
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use Symfony\Component\Stopwatch\Stopwatch;

class RecommendationExecutor
{
    protected $discoveryExecutor;

    protected $postProcessExecutor;

    protected $stopwatch;

    public function __construct(DatabaseService $databaseService)
    {
        $this->discoveryExecutor = new DiscoveryPhaseExecutor($databaseService);
        $this->postProcessExecutor = new PostProcessPhaseExecutor($databaseService);
        $this->stopwatch = new Stopwatch();
    }

    public function processRecommendation(NodeInterface $input, RecommendationEngine $engine)
    {
        $recommendations = new Recommendations();
        $this->stopwatch->start('discovery');
        $discoveryResult = $this->discoveryExecutor->processDiscovery($input, $engine->getDiscoveryEngines(), $engine->getBlacklistBuilders());
        $blacklist = $this->buildBlacklistedNodes($discoveryResult, $engine);
        foreach ($engine->getDiscoveryEngines() as $discoveryEngine) {
            $recommendations->merge($discoveryEngine->produceRecommendations($input, $discoveryResult));
        }
        $discoveryTime = $this->stopwatch->stop('discovery');
        $this->removeIrrelevant($input, $engine, $recommendations, $blacklist);

        $this->stopwatch->start('post_process');
        $postProcessResult = $this->postProcessExecutor->execute($input, $recommendations, $engine);
        foreach ($engine->getPostProcessors() as $postProcessor) {
            $tag = $postProcessor->name();
            $result = $postProcessResult->get($tag);
            $postProcessor->handleResultSet($input, $result, $recommendations);
        }
        $pPTime = $this->stopwatch->stop('post_process');
        $recommendations->sort();

        return $recommendations;
    }

    public function removeIrrelevant(NodeInterface $input, RecommendationEngine $engine, Recommendations $recommendations, array $blacklist)
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
