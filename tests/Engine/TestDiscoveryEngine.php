<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Tests\Engine;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class TestDiscoveryEngine extends SingleDiscoveryEngine
{
    public function query()
    {
        return "MATCH (n) RETURN n";
    }

    public function name()
    {
        return "test_discovery";
    }

}