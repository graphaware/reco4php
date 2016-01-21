<?php

namespace GraphAware\Reco4PHP\Tests\Result;

use GraphAware\Reco4PHP\Result\PartialScore;

/**
 * @group result
 * @group score
 */
class PartialScoreTest extends \PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        $partialScore = new PartialScore();
        $this->assertInstanceOf(PartialScore::class, $partialScore);
        $this->assertEquals(0.0, $partialScore->getValue());
        $this->assertCount(0, $partialScore->getReasons());
    }

    public function testItSetTheInitialScoreWhenPassed()
    {
        $partialScore = new PartialScore(13.0);
        $this->assertEquals(13.0, $partialScore->getValue());
    }

    public function testItAddsAReasonWhenDetailsArePassed()
    {
        $partialScore = new PartialScore(1, array('name' => 'value'));
        $this->assertCount(1, $partialScore->getReasons());
        $this->assertEquals("value", $partialScore->getReasons()[0]->getDetails()['name']);
    }

    public function testScoreIsUpdatedWhenAddingAReason()
    {
        $partialScore = new PartialScore(1);
        $this->assertEquals(1, $partialScore->getValue());
        $partialScore->add(5, array('name' => 'because'));
        $this->assertEquals(6, $partialScore->getValue());
        $partialScore->add(-5, array('reason' => 'because'));
        $this->assertEquals(1.0, $partialScore->getValue());
    }
}