<?php

namespace GraphAware\Reco4PHP\Tests\Result;

use GraphAware\Reco4PHP\Context\SimpleContext;
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
        $list = new Recommendations(new SimpleContext());

        $list->add($node, 'e1', new SingleScore(1));
        $list->add($node, 'e2', new SingleScore(1));

        $this->assertEquals(1, $list->size());
        $this->assertEquals(2, $list->getItems()[0]->totalScore());
        $this->assertCount(2, $list->get(0)->getScores());
        $this->assertArrayHasKey('e1', $list->get(0)->getScores());
        $this->assertArrayHasKey('e2', $list->get(0)->getScores());
    }

    public function testTotalScoreIsIncremented()
    {
        $node = FakeNode::createDummy();
        $list = new Recommendations(new SimpleContext());

        $list->add($node, 'e1', new SingleScore(1));
        $reco = $list->getItems()[0];
        $this->assertEquals(1, $reco->totalScore());
        $reco->addScore('score2', new SingleScore(1));
        $this->assertEquals(2, $reco->totalScore());
    }

    public function testReasons()
    {
        $node = FakeNode::createDummy();
        $list = new Recommendations(new SimpleContext());

        $list->add($node, 'disc1', new SingleScore(1, 'reason1'));
        $list->add($node, 'disc1', new SingleScore(1, 'reason2'));
        $list->add($node, 'disc2', new SingleScore(3, 'reason3'));
        for ($i = 0; $i < 10; ++$i) {
            $list->add($node, 'disc3', new SingleScore(1, 'reasond'.$i));
        }
        $reco = $list->get(0);
        $this->assertEquals(15, $reco->totalScore());
        $this->assertArrayHasKey('disc3', $reco->getScores());
        $this->assertCount(10, $reco->getScore('disc3')->getScores());
    }
}