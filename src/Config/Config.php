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

interface Config
{
    const UNLIMITED = PHP_INT_MAX;

    /**
     * @return int maximum number of items to recommend
     */
    public function limit();

    /**
     * @return int maximum number of ms the recommendation-computing process should take. Note that it is
     *             for information only, it is the responsibility of the engines to honour this configuration or not.
     */
    public function maxTime();
}
