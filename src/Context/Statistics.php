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

use Symfony\Component\Stopwatch\Stopwatch;

class Statistics
{
    private static $DISCOVERY_KEY = 'discovery';
    private static $POST_PROCESS_KEY = 'post_process';
    private static $TOTAL_KEY = 'total';

    protected Stopwatch $stopwatch;

    protected float $discoveryTime;

    protected float $postProcessingTime;

    public function __construct()
    {
        $this->stopwatch = new Stopwatch();
    }

    public function startDiscovery(): void
    {
        $this->stopwatch->start(self::$DISCOVERY_KEY);
    }

    public function stopDiscovery(): void
    {
        $e = $this->stopwatch->stop(self::$DISCOVERY_KEY);
        $this->discoveryTime = $e->getDuration();
    }

    public function startPostProcess(): void
    {
        $this->stopwatch->start(self::$POST_PROCESS_KEY);
    }

    public function stopPostProcess(): void
    {
        $e = $this->stopwatch->stop(self::$POST_PROCESS_KEY);
        $this->postProcessingTime = $e->getDuration();
    }

    public function getTimes(): array
    {
        return [
            self::$DISCOVERY_KEY => $this->discoveryTime,
            self::$POST_PROCESS_KEY => $this->postProcessingTime,
            self::$TOTAL_KEY => $this->discoveryTime + $this->postProcessingTime,
        ];
    }

    public function getCurrentTimeSpent(): float
    {
        $discovery = null !== $this->discoveryTime ? $this->discoveryTime : 0.0;
        $postProcess = null !== $this->postProcessingTime ? $this->postProcessingTime : 0.0;

        return $discovery + $postProcess;
    }
}
