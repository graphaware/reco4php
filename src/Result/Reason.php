<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Result;

class Reason
{
    protected $value;

    protected $details;

    public function __construct($value, array $details)
    {
        $this->notNull($details);
        $this->value = (float) $value;
        $this->details = $details;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function notNull($v)
    {
        if (!is_array($v) || empty($v)) {
            throw new \InvalidArgumentException(sprintf('a detail should be of type array and cannot be null, "%s" given', $v));
        }

        foreach ($v as $k => $value) {
            if (!is_string($k)) {
                throw new \InvalidArgumentException(sprintf('a detail\'s key should be of type string, "%s" given', $k));
            }
        }
    }
}