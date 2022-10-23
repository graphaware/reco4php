<?php

declare(strict_types=1);

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
use GraphAware\Reco4PHP\Executor\RecommendationExecutor;
use GraphAware\Reco4PHP\Filter\BlackListBuilder;
use GraphAware\Reco4PHP\Filter\Filter;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Post\PostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use Laudis\Neo4j\Types\Node;

abstract class BaseRecommendationEngine implements RecommendationEngine
{
    private DatabaseService $databaseService;

    private RecommendationExecutor $recommendationExecutor;

    /**
     * {@inheritdoc}
     */
    public function discoveryEngines(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function blacklistBuilders(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function postProcessors(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    final public function getDiscoveryEngines(): array
    {
        return array_filter($this->discoveryEngines(), function (DiscoveryEngine $discoveryEngine) {
            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    final public function getBlacklistBuilders(): array
    {
        return array_filter($this->blacklistBuilders(), function (BlackListBuilder $blackListBuilder) {
            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    final public function getFilters(): array
    {
        return array_filter($this->filters(), function (Filter $filter) {
            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    final public function getPostProcessors(): array
    {
        return array_filter($this->postProcessors(), function (PostProcessor $postProcessor) {
            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    final public function recommend(Node $input, Context $context): Recommendations
    {
        return $this->recommendationExecutor->processRecommendation($input, $this, $context);
    }

    /**
     * {@inheritdoc}
     */
    final public function setDatabaseService(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
        $this->recommendationExecutor = new RecommendationExecutor($this->databaseService);
    }
}
