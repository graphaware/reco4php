<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Persistence;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\ClientInterface;

class DatabaseService
{
    private ClientInterface $driver;

    public function __construct($uri = null)
    {
        if (null !== $uri) {
            $this->driver = ClientBuilder::create()
                ->withDriver('default', $uri)
                ->withDefaultDriver('default')
                ->build();
        }
    }

    public function getDriver(): ClientInterface
    {
        return $this->driver;
    }

    public function setDriver(ClientInterface $driver): void
    {
        $this->driver = $driver;
    }
}
