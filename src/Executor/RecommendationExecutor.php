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
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use GraphAware\Reco4PHP\Result\Score;

class RecommendationExecutor
{
    protected $discoveryExecutor;

    public function __construct(DatabaseService $databaseService)
    {
        $this->discoveryExecutor = new DiscoveryPhaseExecutor($databaseService);
    }

    public function processRecommendation(NodeInterface $input, RecommendationEngine $engine)
    {
        $recommendations = new Recommendations();
        $discoveryResult = $this->discoveryExecutor->processDiscovery($input, $engine->engines());
        foreach ($engine->engines() as $discoveryEngine) {
            $this->getRecommendationsFromResult($discoveryResult, $discoveryEngine, $recommendations);
        }

        return $recommendations;
    }

    public function getRecommendationsFromResult(ResultCollection $resultCollection, DiscoveryEngine $engine, Recommendations $recommendations)
    {
        $result = $resultCollection->get($engine->name());
        foreach ($result->records() as $record) {
            $recommendations->add($record->value("reco"), new Score($record->value("score")));
        }
    }
}