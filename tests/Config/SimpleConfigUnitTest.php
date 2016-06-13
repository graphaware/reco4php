<?php

namespace GraphAware\Reco4PHP\Tests\Config;

use GraphAware\Reco4PHP\Config\SimpleConfig;
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;
use GraphAware\Common\Type\Node;

class SimpleConfigUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $config = new SimpleConfig();
        $this->assertEquals(PHP_INT_MAX, $config->limit());
        $this->assertEquals(PHP_INT_MAX, $config->maxTime());
        $this->assertFalse($config->containsKey('key'));
    }

    public function testCustomLimitAndTime()
    {
        $config = new SimpleConfig(100, 1000);
        $this->assertEquals(100, $config->limit());
        $this->assertEquals(1000, $config->maxTime());
    }

    public function testConfigExtendsKeyValue()
    {
        $config = new SimpleConfig();
        $config->add('month', 'june');
        $config->add('obj', FakeNode::createDummy());
        $this->assertEquals('june', $config->get('month'));
        $this->assertInstanceOf(Node::class, $config->get('obj'));
    }
}