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
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;

/**
 * @group engine
 */
class SingleDiscoveryEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        $engine = new TestDiscoveryEngine();
        $this->assertInstanceOf(SingleDiscoveryEngine::class, $engine);
        $this->assertEquals("MATCH (n) RETURN n", $engine->query());
        $this->assertEquals("inputId", $engine->idParamName());
        $this->assertEquals("score", $engine->scoreResultName());
        $this->assertEquals("reco", $engine->recoResultName());
        $this->assertEquals(1, $engine->defaultScore());
        $this->assertEquals("test_discovery", $engine->name());
    }

    public function testParametersBuilding()
    {
        $engine = new TestDiscoveryEngine();
        $input = FakeNode::createDummy();
        $engine->buildParams($input);
        $this->assertEquals($input->identity(), $engine->parameters()['inputId']);
        $this->assertCount(1, $engine->parameters());
    }

    public function testOverride()
    {
        $engine = new OverrideDiscoveryEngine();
        $input = FakeNode::createDummy();
        $engine->buildParams($input);
        $this->assertCount(2, $engine->parameters());
        $this->assertEquals("php", $engine->parameters()['language']);
        $this->assertEquals("recommendation", $engine->recoResultName());
        $this->assertEquals("rate", $engine->scoreResultName());
        $this->assertEquals("source", $engine->idParamName());
        $this->assertEquals(10, $engine->defaultScore());
    }
}