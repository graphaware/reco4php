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

use GraphAware\Reco4PHP\Executor\RecommendationExecutor;
use GraphAware\Reco4PHP\Filter\BlackListBuilder;
use GraphAware\Reco4PHP\Filter\Filter;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Post\PostProcessor;

abstract class BaseRecommendationEngine implements RecommendationEngine
{
    /**
     * @var \GraphAware\Reco4PHP\Persistence\DatabaseService
     */
    private $databaseService;

    /**
     * @var \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    private $engines = [];

    /**
     * @var \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    private $blacklistBuilders = [];

    /**
     * @var \GraphAware\Reco4PHP\Filter\Filter[]
     */
    private $filters = [];

    /**
     * @var \Psr\Log\LoggerInterface[]
     */
    private $loggers = [];

    /**
     * @var \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    private $postProcessors = [];

    /**
     * @var \GraphAware\Reco4PHP\Executor\RecommendationExecutor
     */
    private $recommendationExecutor;

    /**
     * BaseRecommendationEngine constructor.
     */
    public function __construct()
    {
        $this->buildEngines();
        $this->buildBlackListBuilders();
        $this->buildFilters();
        $this->buildPostProcessors();
        $this->loggers = $this->loggers();
    }

    /**
     * {@inheritdoc}
     */
    public function discoveryEngines()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function blacklistBuilders()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function filters()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function postProcessors()
    {
        return array();
    }

    public function loggers()
    {
        return array();
    }

    /**
     * @return \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    final public function getDiscoveryEngines()
    {
        return $this->engines;
    }

    /**
     * @return \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    final public function getBlacklistBuilders()
    {
        return $this->blacklistBuilders;
    }

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    final public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    final public function getPostProcessors()
    {
        return $this->postProcessors;
    }

    /**
     * @return array|\Psr\Log\LoggerInterface[]
     */
    final public function getLoggers()
    {
        return $this->loggers;
    }

    /**
     * @param \GraphAware\Common\Type\Node $input
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    final public function recommend(Node $input)
    {
        $recommendations = $this->recommendationExecutor->processRecommendation($input, $this);

        return $recommendations;
    }

    final public function setDatabaseService(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
        $this->recommendationExecutor = new RecommendationExecutor($this->databaseService);
    }

    private function buildEngines()
    {
        $engines = $this->discoveryEngines();
        if (!is_array($engines) && !$engines instanceof \Traversable) {
            throw new \RuntimeException(sprintf('The %s::engines() method should return an array of SingleDiscoveryEngine instances', get_class($this)));
        }

        foreach ($engines as $engine) {
            if (!$engine instanceof SingleDiscoveryEngine) {
                throw new \RuntimeException(sprintf('Engine is not an instance of "%s"', SingleDiscoveryEngine::class));
            }
            $this->engines[] = $engine;
        }
    }

    private function buildBlackListBuilders()
    {
        $blackListBuilders = $this->blacklistBuilders();
        if (!is_array($blackListBuilders) && !$blackListBuilders instanceof \Traversable) {
            throw new \RuntimeException(sprintf('The %s::blacklistBuilders() method should return an array of BlackListBuilder instances', get_class($this)));
        }

        foreach ($blackListBuilders as $blackListBuilder) {
            if (!$blackListBuilder instanceof BlackListBuilder) {
                throw new \RuntimeException(sprintf('The given blacklist builder is not an instance of %s', BlackListBuilder::class));
            }

            $this->blacklistBuilders[] = $blackListBuilder;
        }
    }

    private function buildFilters()
    {
        $filters = $this->filters();
        if (!is_array($filters) && $filters instanceof \Traversable) {
            throw new \RuntimeException(sprintf('The %s::filters() method should return an array of Filter instances', get_class($this)));
        }

        foreach ($filters as $filter) {
            if (!$filter instanceof Filter) {
                throw new \RuntimeException(sprintf('The given filter is not an instance of %s', Filter::class));
            }

            $this->filters[] = $filter;
        }
    }

    private function buildPostProcessors()
    {
        $postProcessors = $this->postProcessors();
        if (!is_array($postProcessors) && !$postProcessors instanceof \Traversable) {
            throw new \RuntimeException(sprintf('The %s::postProcessors() method should return an array of PostProcessor instances', get_class($this)));
        }

        foreach ($postProcessors as $postProcessor) {
            if (!$postProcessor instanceof PostProcessor) {
                throw new \RuntimeException(sprintf('The given post processor is not an instance of %s', PostProcessor::class));
            }

            $this->postProcessors[] = $postProcessor;
        }
    }
}
