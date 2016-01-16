<?php

namespace GraphAware\Reco4PHP\Tests\Result;

use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\Score;
use GraphAware\Reco4PHP\Tests\Helper\NodeProxy;

/**
 * Class RecommendationsListTest
 * @package GraphAware\Reco4PHP\Tests\Result
 *
 * @group result
 */
class RecommendationsListTest extends \PHPUnit_Framework_TestCase
{
    public function testResultGetTwoScoresIfDiscoveredTwice()
    {
        $node = NodeProxy::createDummy();
        $list = new Recommendations();

        $list->add($node, new Score(1));
        $list->add($node, new Score(1));

        $this->assertEquals(1, $list->size());
        $this->assertEquals(2, $list->getItems()[0]->totalScore());
        $this->assertCount(2, $list->getItems()[0]->scores());
    }
}