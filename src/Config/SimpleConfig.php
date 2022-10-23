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

class SimpleConfig extends KeyValueConfig
{
    protected int $limit;

    protected int $maxTime;

    public function __construct(?int $limit = null, ?int $maxTime = null)
    {
        $this->limit = $limit ?? self::UNLIMITED;
        $this->maxTime = $maxTime ?? self::UNLIMITED;
    }

    /**
     * {@inheritdoc}
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function maxTime(): int
    {
        return $this->maxTime;
    }
}
