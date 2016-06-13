<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Context;

use GraphAware\Reco4PHP\Config\Config;

interface Context
{
    /**
     * @return \GraphAware\Reco4PHP\Config\Config
     */
    public function config() : Config;

    /**
     * @return bool
     */
    public function timeLeft() : bool;

    /**
     * @return \GraphAware\Reco4PHP\Context\Statistics
     */
    public function getStatistics() : Statistics;
}
