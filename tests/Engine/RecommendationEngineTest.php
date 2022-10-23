<?php

namespace GraphAware\Reco4PHP\Tests\Engine;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use PHPUnit\Framework\TestCase;

class RecommendationEngineTest extends TestCase
{
    public function testWiring(): void
    {
        $stub = $this->getMockForAbstractClass(BaseRecommendationEngine::class);
    }
}
