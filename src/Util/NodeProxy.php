<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Util;

use GraphAware\Common\Type\Node;

class NodeProxy implements Node
{
    protected $id;

    protected $properties = [];

    protected $labels = [];

    public function __construct($id = null, array $properties = array(), array $labels = array())
    {
        $this->id = $id;
        $this->properties = $properties;
        $this->labels = $labels;
    }

    public function identity()
    {
        return $this->id;
    }

    public function keys()
    {
        return array_keys($this->properties);
    }

    public function containsKey($key)
    {
        return array_key_exists($key, $this->properties);
    }

    public function get($key)
    {
        if (!$this->containsKey($key)) {
            throw new \InvalidArgumentException(sprintf('This node doesn\'t contain the "%s" property'), $key);
        }

        return $this->properties[$key];
    }

    public function hasValue($key)
    {
        return $this->containsKey($key);
    }

    public function value($key, $default = null)
    {
        if (!$this->containsKey($key) && 1 === func_num_args()) {
            throw new \InvalidArgumentException(sprintf('This node doesn\'t contain the "%s" property'), $key);
        }

        return $this->containsKey($key) ? $this->properties[$key] : $default;
    }

    public function values()
    {
        return $this->properties;
    }

    public function asArray()
    {
        return [
            'id' => $this->id,
            'labels' => $this->labels,
            'properties' => $this->properties,
        ];
    }

    public function labels()
    {
        return $this->labels;
    }

    public function hasLabel($label)
    {
        return in_array($label, $this->labels);
    }
}
