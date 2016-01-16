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
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Common\Type\NodeInterface;

abstract class BaseRecommendationEngine implements RecommendationEngine
{
    protected $databaseService;

    protected $engines;

    protected $blacklistBuilders;

    protected $filters;

    protected $loggers;

    protected $recommendationExecutor;

    public function __construct()
    {
        $this->engines = $this->engines();
        $this->blacklistBuilders = $this->blacklistBuilders();
        $this->filters = $this->filters();
        $this->loggers = $this->loggers();
    }

    /**
     * @param \GraphAware\Common\Type\NodeInterface $input
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    final public function recommend(NodeInterface $input)
    {
        $this->recommendationExecutor = new RecommendationExecutor($this->databaseService);
        $recommendations = $this->recommendationExecutor->processRecommendation($input, $this);

        // @todo filters and blacklists

        // @todo post processors

        return $recommendations;
    }

    final public function setDatabaseService(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }
}