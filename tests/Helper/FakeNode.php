<?php

namespace GraphAware\Reco4PHP\Tests\Helper;

use GraphAware\Common\Type\Node;

class FakeNode implements Node
{
    protected $identity;

    protected $labels = [];

    function __get($name)
    {
        return null;
    }


    public function __construct($identity, array $labels = array())
    {
        $this->identity = $identity;
        $this->labels = $labels;
    }

    public static function createDummy($id = null)
    {
        $identity = null !== $id ? $id : rand(0,1000);
        return new self($identity, array("Dummy"));
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

    public function keys()
    {
        // TODO: Implement keys() method.
    }

    public function containsKey($key)
    {
        // TODO: Implement containsKey() method.
    }

    public function get($key)
    {
        // TODO: Implement get() method.
    }

    public function values()
    {
        // TODO: Implement values() method.
    }

    public function asArray()
    {
        // TODO: Implement asArray() method.
    }

    public function hasValue($key)
    {
        // TODO: Implement hasValue() method.
    }

    public function value($key, $default = null)
    {
        // TODO: Implement value() method.
    }


}