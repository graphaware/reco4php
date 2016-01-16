<?php

namespace GraphAware\Reco4PHP\Tests\Engine;

use GraphAware\Reco4PHP\Engine\SingleRecommendationEngine;
use GraphAware\Reco4PHP\Engine\BaseCypherEngine;
use GraphAware\Reco4PHP\Engine\CypherEngine;

/**
 *
 * @group engine
 * @group cypher-engine
 */
class CypherEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testEngineInstance()
    {
        $engine = new DummyCypherEngine();
        $this->assertEquals("dummy-engine", $engine->name());
        $this->assertEquals("reco", $engine->recoResultName());
        $this->assertEquals("score", $engine->scoreResultName());

        $this->assertInstanceOf(CypherEngine::class, $engine);
        $this->assertInstanceOf(BaseCypherEngine::class, $engine);
        $this->assertInstanceOf(SingleRecommendationEngine::class, $engine);
    }
}