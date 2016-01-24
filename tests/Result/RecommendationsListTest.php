<?php

namespace GraphAware\Reco4PHP\Tests\Result;

use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\Score;
use GraphAware\Reco4PHP\Result\SingleScore;
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;

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
        $node = FakeNode::createDummy();
        $list = new Recommendations();

        $list->add($node, 'e1', new SingleScore(1));
        $list->add($node, 'e2', new SingleScore(1));

        $this->assertEquals(1, $list->size());
        $this->assertEquals(2, $list->getItems()[0]->totalScore());
        $this->assertCount(2, $list->get(0)->getScores());
        $this->assertArrayHasKey('e1', $list->get(0)->getScores());
        $this->assertArrayHasKey('e2', $list->get(0)->getScores());
    }
}