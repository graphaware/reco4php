<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP;

use GraphAware\Reco4PHP\Persistence\DatabaseService;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use GraphAware\Reco4PHP\Engine\RecommendationEngine;

class RecommenderService
{
    private $engines = [];

    private $databaseService;

    private $eventDispatcher;

    private $logger;

    public function __construct(DatabaseService $databaseService, EventDispatcherInterface $eventDispatcher = null, LoggerInterface $logger = null)
    {
        $this->databaseService = $databaseService;
        $this->eventDispatcher = null !== $eventDispatcher ? $eventDispatcher : new EventDispatcher();
        $this->logger = null !== $logger ? $logger : new NullLogger();
    }

    public function getRecommender($name)
    {
        if (!array_key_exists($name, $this->engines)) {
            throw new \InvalidArgumentException(sprintf('The Recommendation engine "%s" is not registered in the Recommender Service', $name));
        }

        return $this->engines[$name];
    }

    public function registerRecommendationEngine(RecommendationEngine $recommendationEngine)
    {
        var_dump($recommendationEngine->name());
        $recommendationEngine->setDatabaseService($this->databaseService);
        $this->engines[$recommendationEngine->name()] = $recommendationEngine;
    }
}