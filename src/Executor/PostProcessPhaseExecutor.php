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

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Post\PostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Recommendations;

class PostProcessPhaseExecutor
{
    protected $databaseService;

    /**
     * @var \GraphAware\Neo4j\Client\Stack
     */
    protected $stack;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function execute(NodeInterface $input, Recommendations $recommendations, RecommendationEngine $recommendationEngine)
    {
        $this->stack = $this->databaseService->getDriver()->stack("post_process_" . $recommendationEngine->name());

        foreach ($recommendationEngine->postProcessors() as $postProcessor) {
            if ($postProcessor instanceof CypherAwarePostProcessor) {
                foreach ($recommendations->getItems() as $recommendation) {
                    $this->prepareQuery($input, $recommendation, $postProcessor);
                }
            }
        }

        try {
            $results = $this->databaseService->getDriver()->runStack($this->stack);
            $this->stack = null;
            return $results;
        } catch (\Exception $e) {
            throw new \RuntimeException('PostProcess Query Exception - ' . $e->getMessage());
        }
    }

    public function prepareQuery(NodeInterface $input, Recommendation $recommendation, CypherAwarePostProcessor $postProcessor)
    {
        $query = "MATCH (input), (reco) WHERE id(input) = {idInput} AND id(reco) = {idReco}" . PHP_EOL;
        $query .= $postProcessor->query();

        $parameters = [
            'idInput' => $input->identity(),
            'idReco' => $recommendation->item()->identity()
        ];

        $tag = sprintf('post_process_%s_%d', $postProcessor->name(), $recommendation->item()->identity());

        $this->stack->push($query, $parameters, $tag);
    }
}