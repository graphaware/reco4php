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
    protected array $values = [];

    public function get(string $key): mixed
    {
        return array_key_exists($key, $this->values) ? $this->values[$key] : null;
    }

    public function add(string $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }

    public function containsKey(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function contains(mixed $o): bool
    {
        return in_array($o, $this->values);
    }
}
