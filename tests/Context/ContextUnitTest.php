<?php

namespace GraphAware\Reco4PHP\Tests\Context;

use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;
use GraphAware\Reco4PHP\Config\Config;

class ContextUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $input = FakeNode::createDummy();
        $context = new SimpleContext();
        $this->assertInstanceOf(Config::class, $context->config());
    }
}

