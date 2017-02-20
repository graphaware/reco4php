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

use GraphAware\Neo4j\Client\ClientInterface;

class ProvidedDatabaseService extends DatabaseService
{
    public function __construct(ClientInterface $client)
    {
        parent::__construct('localhost:7474');

        $this->driver = $client;
    }
}