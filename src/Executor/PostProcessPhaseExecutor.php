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

use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\ResultCollection;
use Laudis\Neo4j\Types\Node;

class PostProcessPhaseExecutor
{
    protected DatabaseService $databaseService;

    /**
     * PostProcessPhaseExecutor constructor.
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function execute(Node $input, Recommendations $recommendations, RecommendationEngine $recommendationEngine): ResultCollection
    {
        $statements = [];
        $tags = [];

        foreach ($recommendationEngine->getPostProcessors() as $postProcessor) {
            if ($postProcessor instanceof CypherAwarePostProcessor) {
                foreach ($recommendations->getItems() as $recommendation) {
                    $tags[] = sprintf('post_process_%s_%d', $postProcessor->name(), $recommendation->item()->identity());
                    $statements[] = $postProcessor->buildQuery($input, $recommendation);
                }
            } elseif ($postProcessor instanceof RecommendationSetPostProcessor) {
                $statements[] = $postProcessor->buildQuery($input, $recommendations);
                $tags[] = $postProcessor->name();
            }
        }

        try {
            $resultCollection = new ResultCollection();
            foreach ($this->databaseService->getDriver()->runStatements($statements) as $key => $value) {
                $resultCollection->add($value, $tags[$key]);
            }

            return $resultCollection;
        } catch (\Exception $e) {
            throw new \RuntimeException('PostProcess Query Exception - '.$e->getMessage());
        }
    }
}
