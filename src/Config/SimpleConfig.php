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
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $maxTime;

    /**
     * @param int|null $limit
     * @param int|null $maxTime
     */
    public function __construct($limit = null, $maxTime = null)
    {
        $this->limit = null !== $limit ? $limit : self::UNLIMITED;
        $this->maxTime = null !== $maxTime ? $maxTime : self::UNLIMITED;
    }

    /**
     * {@inheritdoc}
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function maxTime()
    {
        return $this->maxTime;
    }
}
