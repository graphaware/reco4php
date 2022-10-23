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

use GraphAware\Reco4PHP\Config\SimpleConfig;
use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;
use Laudis\Neo4j\Databags\Statement;
use PHPUnit\Framework\TestCase;

/**
 * @group engine
 */
class SingleDiscoveryEngineTest extends TestCase
{
    public function testInit(): void
    {
        $engine = new TestDiscoveryEngine();
        $this->assertInstanceOf(SingleDiscoveryEngine::class, $engine);
        $input = FakeNode::createDummy();
        $this->assertInstanceOf(Statement::class, $engine->discoveryQuery($input, new SimpleContext()));
        $this->assertEquals('MATCH (n) WHERE id(n) <> $inputId RETURN n', $engine->discoveryQuery($input, new SimpleContext())->getText());
        $this->assertEquals('score', $engine->scoreResultName());
        $this->assertEquals('reco', $engine->recoResultName());
        $this->assertEquals(1, $engine->defaultScore());
        $this->assertEquals('test_discovery', $engine->name());
    }

    public function testParametersBuilding(): void
    {
        $engine = new TestDiscoveryEngine();
        $input = FakeNode::createDummy();
        $this->assertEquals($input->getId(), $engine->discoveryQuery($input, new SimpleContext())->getParameters()['inputId']);
        $this->assertCount(1, $engine->discoveryQuery($input, new SimpleContext())->getParameters());
    }

    public function testOverride(): void
    {
        $engine = new OverrideDiscoveryEngine();
        $input = FakeNode::createDummy();
        $context = new SimpleContext(new SimpleConfig());
        $this->assertCount(2, $engine->discoveryQuery($input, $context)->getParameters());
        $this->assertEquals($input->getId(), $engine->discoveryQuery($input, $context)->getParameters()['input']);
        $this->assertEquals('recommendation', $engine->recoResultName());
        $this->assertEquals('rate', $engine->scoreResultName());
        $this->assertEquals('source', $engine->idParamName());
        $this->assertEquals(10, $engine->defaultScore());
    }
}
