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
use GraphAware\Reco4PHP\Config\SimpleConfig;

class SimpleContext implements Context
{
    protected Config $config;

    protected Statistics $statistics;

    public function __construct(?Config $config = null)
    {
        $this->config = $config ?? new SimpleConfig();
        $this->statistics = new Statistics();
    }

    /**
     * {@inheritdoc}
     */
    public function config(): Config
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function timeLeft(): bool
    {
        return $this->statistics->getCurrentTimeSpent() < $this->config()->limit();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics(): Statistics
    {
        return $this->statistics;
    }
}
