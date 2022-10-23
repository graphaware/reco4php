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

use GraphAware\Reco4PHP\Engine\RecommendationEngine;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use InvalidArgumentException;
use Laudis\Neo4j\Contracts\ClientInterface;
use Laudis\Neo4j\Types\CypherList;
use Laudis\Neo4j\Types\Node;

class RecommenderService
{
    /**
     * @var RecommendationEngine[]
     */
    private array $engines = [];

    private DatabaseService $databaseService;

    /**
     * RecommenderService constructor.
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @param string $uri
     *
     * @return RecommenderService
     */
    public static function create($uri)
    {
        return new self(new DatabaseService($uri));
    }

    /**
     * @return RecommenderService
     */
    public static function createWithClient(ClientInterface $client)
    {
        $databaseService = new DatabaseService();
        $databaseService->setDriver($client);

        return new self($databaseService);
    }

    public function findInputById(int $id): Node
    {
        $result = $this->databaseService->getDriver()->run('MATCH (n) WHERE id(n) = $id RETURN n as input', ['id' => $id]);

        return $this->validateInput($result);
    }

    public function findInputBy(string $label, string $key, mixed $value): Node
    {
        $query = sprintf('MATCH (n:%s {%s: $value }) RETURN n as input', $label, $key);
        $result = $this->databaseService->getDriver()->run($query, ['value' => $value]);

        return $this->validateInput($result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateInput(CypherList $results): Node
    {
        if (count($results) < 1 || !$results->first()->get('input') instanceof Node) {
            throw new InvalidArgumentException(sprintf('Node not found'));
        }

        return $results->first()->get('input');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getRecommender(string $name): RecommendationEngine
    {
        if (!array_key_exists($name, $this->engines)) {
            throw new InvalidArgumentException(sprintf('The Recommendation engine "%s" is not registered in the Recommender Service', $name));
        }

        return $this->engines[$name];
    }

    public function registerRecommendationEngine(RecommendationEngine $recommendationEngine): void
    {
        $recommendationEngine->setDatabaseService($this->databaseService);
        $this->engines[$recommendationEngine->name()] = $recommendationEngine;
    }
}
