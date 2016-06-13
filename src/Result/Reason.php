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

    protected $detail;

    public function __construct($value, $detail)
    {
        $this->value = (float) $value;
        $this->detail = $detail;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDetail()
    {
        return $this->detail;
    }
}
