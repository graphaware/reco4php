<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Transactional;

abstract class BaseCypherAware implements CypherAware
{
    private $parameters = [];

    final protected function addParameter($key, $value)
    {
        if (!is_scalar($value) && !is_array($value)) {
            throw new \InvalidArgumentException(sprintf("Expected a scalar or array value for '%s'", $key));
        }
        $this->parameters[$key] = $value;
    }

    final protected function addParameters(array $parameters)
    {
        foreach ($parameters as $key => $parameter) {
            $this->addParameter($key, $parameter);
        }
    }

    final public function parameters()
    {
        return $this->parameters;
    }
}
