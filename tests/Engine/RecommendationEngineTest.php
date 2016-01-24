<?php

namespace GraphAware\Reco4PHP\Tests\Engine;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;

class RecommendationEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testWiring()
    {
        $stub = $this->getMockForAbstractClass(BaseRecommendationEngine::class);
    }
}