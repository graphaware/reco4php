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

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Persistence\DatabaseService;

interface RecommendationEngine
{
    public function name();

    public function engines();

    public function blacklistBuilders();

    /**
     * @return \GraphAware\Reco4PHP\Post\PostProcessor[]
     */
    public function postProcessors();

    /**
     * @return \GraphAware\Reco4PHP\Filter\Filter[]
     */
    public function filters();

    public function loggers();

    /**
     * @param \GraphAware\Common\Type\NodeInterface $input
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    public function recommend(NodeInterface $input);

    public function setDatabaseService(DatabaseService $databaseService);
}