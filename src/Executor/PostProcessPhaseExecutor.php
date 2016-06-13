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

use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;

class PostProcessPhaseExecutor
{
    /**
     * @var \GraphAware\Reco4PHP\Persistence\DatabaseService
     */
    protected $databaseService;

    /**
     * PostProcessPhaseExecutor constructor.
     *
     * @param \GraphAware\Reco4PHP\Persistence\DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @param \GraphAware\Common\Type\Node                     $input
     * @param \GraphAware\Reco4PHP\Result\Recommendations      $recommendations
     * @param \GraphAware\Reco4PHP\Engine\RecommendationEngine $recommendationEngine
     *
     * @return \GraphAware\Common\Result\ResultCollection
     */
    public function execute(Node $input, Recommendations $recommendations, RecommendationEngine $recommendationEngine)
    {
        $stack = $this->databaseService->getDriver()->stack('post_process_'.$recommendationEngine->name());

        foreach ($recommendationEngine->getPostProcessors() as $postProcessor) {
            if ($postProcessor instanceof CypherAwarePostProcessor) {
                foreach ($recommendations->getItems() as $recommendation) {
                    $tag = sprintf('post_process_%s_%d', $postProcessor->name(), $recommendation->item()->identity());
                    $statement = $postProcessor->buildQuery($input, $recommendation);
                    $stack->push($statement->text(), $statement->parameters(), $tag);
                }
            } elseif ($postProcessor instanceof RecommendationSetPostProcessor) {
                $statement = $postProcessor->buildQuery($input, $recommendations);
                $stack->push($statement->text(), $statement->parameters(), $postProcessor->name());
            }
        }

        try {
            $results = $this->databaseService->getDriver()->runStack($stack);

            return $results;
        } catch (\Exception $e) {
            throw new \RuntimeException('PostProcess Query Exception - '.$e->getMessage());
        }
    }
}
