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
    private $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function processDiscovery(NodeInterface $input, array $engines)
    {
        $stack = $this->databaseService->getDriver()->stack();
        foreach ($engines as $engine) {
            /** @var \GraphAware\Reco4PHP\Engine\DiscoveryEngine $engine */
            $engine->buildParams($input);
            $query = $this->inputQueryPart($input) . $engine->query();
            $stack->push($query, $engine->parameters(), $engine->name());
        }

        try {
            $resultCollection = $this->databaseService->getDriver()->runStack($stack);
            return $resultCollection;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    private function inputQueryPart(NodeInterface $input)
    {
        return "MATCH (input) WHERE id(input) = {inputId}" . PHP_EOL;
    }
}