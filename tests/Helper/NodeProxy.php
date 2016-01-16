<?php

namespace GraphAware\Reco4PHP\Tests\Helper;

use GraphAware\Common\Type\NodeInterface;

class NodeProxy implements NodeInterface
{
    protected $identity;

    protected $labels = [];

    public function __construct($identity, array $labels = array())
    {
        $this->identity = $identity;
        $this->labels = $labels;
    }

    public static function createDummy()
    {
        return new self(rand(0,1000), array("Dummy"));
    }

    function identity()
    {
        return $this->identity;
    }

    function labels()
    {
        return $this->labels;
    }

    function hasLabel($label)
    {
        return in_array($label, $this->labels);
    }

}