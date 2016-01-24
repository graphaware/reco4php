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
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\DiscoveryEngine;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use Symfony\Component\Stopwatch\Stopwatch;
use GraphAware\Reco4PHP\Result\Score;

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
        $discoveryResult = $this->discoveryExecutor->processDiscovery($input, $engine->engines());
        foreach ($engine->engines() as $discoveryEngine) {
            $recommendations->merge($discoveryEngine->produceRecommendations($input, $discoveryResult));
        }
        $discoveryTime = $this->stopwatch->stop('discovery');
        echo $discoveryTime->getDuration().PHP_EOL;

        $this->removeIrrelevant($input, $engine, $recommendations);

        $this->stopwatch->start('post_process');
        $postProcessResult = $this->postProcessExecutor->execute($input, $recommendations, $engine);
        foreach ($engine->postProcessors() as $postProcessor) {
            foreach ($recommendations->getItems() as $recommendation) {
                if ($postProcessor instanceof CypherAwarePostProcessor) {
                    $tag = sprintf('post_process_%s_%d', $postProcessor->name(), $recommendation->item()->identity());
                    $postProcessor->doPostProcess($input, $recommendation, $postProcessResult->get($tag));
                } else {
                    $postProcessor->postProcess($input, $recommendation);
                }
            }
        }
        $pPTime = $this->stopwatch->stop('post_process');

        echo $pPTime->getDuration().PHP_EOL;

        $recommendations->sort();

        return $recommendations;
    }

    public function getRecommendationsFromResult(NodeInterface $input, ResultCollection $resultCollection, DiscoveryEngine $engine, Recommendations $recommendations)
    {
        $result = $resultCollection->get($engine->name());

        foreach ($result->records() as $record) {
            $recommendations->add($record->value('reco'), new Score($engine->buildScore($input, $record->value($engine->recoResultName()), $record)));
        }
    }

    public function removeIrrelevant(NodeInterface $input, RecommendationEngine $engine, Recommendations $recommendations)
    {
        foreach ($recommendations->getItems() as $recommendation) {
            foreach ($engine->filters() as $filter) {
                if (!$filter->doInclude($input, $recommendation->item())) {
                    $recommendations->remove($recommendation);
                }
            }
        }
    }
}
