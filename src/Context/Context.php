<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Context;

interface Context
{
    /**
     * @return \GraphAware\Common\Type\Node
     */
    public function input();

    /**
     * @return \GraphAware\Reco4PHP\Config\Config
     */
    public function config();

    /**
     * @return bool
     */
    public function timeLeft();
}
