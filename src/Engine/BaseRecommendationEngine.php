<?php

declare (strict_types = 1);

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
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Post\PostProcessor;
use GraphAware\Reco4PHP\Result\Recommendations;
use Psr\Log\LoggerInterface;

abstract class BaseRecommendationEngine implements RecommendationEngine
{
    /**
     * @var \GraphAware\Reco4PHP\Persistence\DatabaseService
     */
    private $databaseService;

    /**
     * @var \GraphAware\Reco4PHP\Executor\RecommendationExecutor
     */
    private $recommendationExecutor;

    /**
     * {@inheritdoc}
     */
    public function discoveryEngines() : array
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function blacklistBuilders() : array
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function filters() : array
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function postProcessors() : array
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function loggers() : array
    {
        return array();
    }

    /**
     * @return \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    final public function getDiscoveryEngines() : array
    {
        return array_filter($this->discoveryEngines(), function (DiscoveryEngine $discoveryEngine) {
            return true;
        });
    }

    /**
     * @return \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    final public function getBlacklistBuilders() : array
    {
        return array_filter($this->blacklistBuilders(), function (BlackListBuilder $blackListBuilder) {
            return true;
        });
    }

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    final public function getFilters() : array
    {
        return array_filter($this->filters(), function (Filter $filter) {
            return true;
        });
    }

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    final public function getPostProcessors() : array
    {
        return array_filter($this->postProcessors(), function (PostProcessor $postProcessor) {
            return true;
        });
    }

    /**
     * @return array|\Psr\Log\LoggerInterface[]
     */
    final public function getLoggers() : array
    {
        return array_filter($this->loggers(), function (LoggerInterface $logger) {
            return true;
        });
    }

    /**
     * @param Node    $input
     * @param Context $context
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    final public function recommend(Node $input, Context $context) : Recommendations
    {
        $recommendations = $this->recommendationExecutor->processRecommendation($input, $this, $context);

        return $recommendations;
    }

    final public function setDatabaseService(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
        $this->recommendationExecutor = new RecommendationExecutor($this->databaseService);
    }
}
