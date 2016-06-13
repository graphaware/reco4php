<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Config;

abstract class KeyValueConfig implements Config
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return array_key_exists($key, $this->values) ? $this->values[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function add($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * @param mixed $o
     *
     * @return bool
     */
    public function contains($o)
    {
        return in_array($o, $this->values);
    }
}
