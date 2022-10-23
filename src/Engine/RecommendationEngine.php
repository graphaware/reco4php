<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Engine;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Filter\BlackListBuilder;
use GraphAware\Reco4PHP\Filter\Filter;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\PostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use Laudis\Neo4j\Types\Node;

interface RecommendationEngine
{
    public function name(): string;

    /**
     * @return DiscoveryEngine[]
     */
    public function discoveryEngines(): array;

    /**
     * @return BlackListBuilder[]
     */
    public function blacklistBuilders(): array;

    /**
     * @return PostProcessor[]
     */
    public function postProcessors(): array;

    /**
     * @return Filter[]
     */
    public function filters(): array;

    /**
     * @return DiscoveryEngine[]
     */
    public function getDiscoveryEngines(): array;

    /**
     * @return BlackListBuilder[]
     */
    public function getBlacklistBuilders(): array;

    /**
     * @return Filter[]
     */
    public function getFilters(): array;

    /**
     * @return PostProcessor[]
     */
    public function getPostProcessors(): array;

    public function recommend(Node $input, Context $context): Recommendations;

    public function setDatabaseService(DatabaseService $databaseService);
}
