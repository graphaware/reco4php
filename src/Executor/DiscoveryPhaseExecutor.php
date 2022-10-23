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

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\DiscoveryEngine;
use GraphAware\Reco4PHP\Filter\BlackListBuilder;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Result\ResultCollection;
use Laudis\Neo4j\Types\Node;

class DiscoveryPhaseExecutor
{
    private DatabaseService $databaseService;

    /**
     * DiscoveryPhaseExecutor constructor.
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @param DiscoveryEngine[]  $engines
     * @param BlackListBuilder[] $blacklists
     */
    public function processDiscovery(Node $input, array $engines, array $blacklists, Context $context): ResultCollection
    {
        $statements = [];
        $tags = [];
        foreach (array_values($engines) as $engine) {
            $statements[] = $engine->discoveryQuery($input, $context);
            $tags[] = $engine->name();
        }

        foreach (array_values($blacklists) as $blacklist) {
            $statements[] = $blacklist->blacklistQuery($input);
            $tags[] = $blacklist->name();
        }

        try {
            $resultCollection = new ResultCollection();
            foreach ($this->databaseService->getDriver()->runStatements($statements) as $key => $value) {
                $resultCollection->add($value, $tags[$key]);
            }

            return $resultCollection;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
