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
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Persistence\DatabaseService;

interface RecommendationEngine
{
    /**
     * @return string
     */
    public function name() : string;

    /**
     * @return \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    public function discoveryEngines() : array;

    /**
     * @return \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    public function blacklistBuilders() : array;

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    public function postProcessors() : array;

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    public function filters() : array;

    /**
     * @return \Psr\Log\LoggerInterface[]
     */
    public function loggers() : array;

    /**
     * @return \GraphAware\Reco4PHP\Engine\DiscoveryEngine[]
     */
    public function getDiscoveryEngines() : array;

    /**
     * @return \GraphAware\Reco4PHP\Filter\BlackListBuilder[]
     */
    public function getBlacklistBuilders() : array;

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    public function getFilters() : array;

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    public function getPostProcessors() : array;

    /**
     * @return \Psr\Log\LoggerInterface[]
     */
    public function getLoggers() : array;

    /**
     * @param Node    $input
     * @param Context $context
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    public function recommend(Node $input, Context $context);

    /**
     * @param \GraphAware\Reco4PHP\Persistence\DatabaseService $databaseService
     */
    public function setDatabaseService(DatabaseService $databaseService);
}
