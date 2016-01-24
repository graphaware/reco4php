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
use GraphAware\Reco4PHP\Persistence\DatabaseService;

class DiscoveryPhaseExecutor
{
    /**
     * @var \GraphAware\Reco4PHP\Persistence\DatabaseService
     */
    private $databaseService;

    /**
     * DiscoveryPhaseExecutor constructor.
     *
     * @param \GraphAware\Reco4PHP\Persistence\DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @param \GraphAware\Common\Type\NodeInterface $input
     * @param array                                 $engines
     *
     * @return \GraphAware\Common\Result\ResultCollection
     */
    public function processDiscovery(NodeInterface $input, array $engines)
    {
        $stack = $this->databaseService->getDriver()->stack();
        foreach ($engines as $engine) {
            /* @var \GraphAware\Reco4PHP\Engine\DiscoveryEngine $engine */
            $engine->buildParams($input);
            $query = $this->inputQueryPart($input).$engine->query();
            $stack->push($query, $engine->parameters(), $engine->name());
        }

        try {
            $resultCollection = $this->databaseService->getDriver()->runStack($stack);

            return $resultCollection;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param \GraphAware\Common\Type\NodeInterface $input
     *
     * @return string
     */
    private function inputQueryPart(NodeInterface $input)
    {
        return 'MATCH (input) WHERE id(input) = {inputId}'.PHP_EOL;
    }
}
