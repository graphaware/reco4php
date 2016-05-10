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

use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Persistence\DatabaseService;

interface RecommendationEngine
{
    /**
     * @return string
     */
    public function name();

    /**
     * @return \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    public function discoveryEngines();

    /**
     * @return \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    public function blacklistBuilders();

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    public function postProcessors();

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    public function filters();

    /**
     * @return \Psr\Log\LoggerInterface[]
     */
    public function loggers();

    /**
     * @return \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    public function getDiscoveryEngines();

    /**
     * @return \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    public function getBlacklistBuilders();

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    public function getFilters();

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    public function getPostProcessors();

    /**
     * @return \Psr\Log\LoggerInterface[]
     */
    public function getLoggers();

    /**
     * @param \GraphAware\Common\Type\Node $input
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    public function recommend(Node $input);

    /**
     * @param \GraphAware\Reco4PHP\Persistence\DatabaseService $databaseService
     */
    public function setDatabaseService(DatabaseService $databaseService);
}
