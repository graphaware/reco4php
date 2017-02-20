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

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Neo4j\Client\ClientInterface;

class DatabaseService
{
    private $driver;

    public function __construct($uri = null)
    {
        if ($uri !== null) {
            $this->driver = ClientBuilder::create()
                ->addConnection('default', $uri)
                ->build();
        }
    }

    /**
     * @return ClientInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param \GraphAware\Neo4j\Client\ClientInterface $driver
     */
    public function setDriver(ClientInterface $driver)
    {
        $this->driver = $driver;
    }
}
