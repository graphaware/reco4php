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

use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Config\Config;
use GraphAware\Reco4PHP\Config\SimpleConfig;

class SimpleContext implements Context
{
    /**
     * @var \GraphAware\Common\Type\Node
     */
    protected $input;

    /**
     * @var \GraphAware\Reco4PHP\Config\Config
     */
    protected $config;

    /**
     * @param \GraphAware\Common\Type\Node       $input
     * @param \GraphAware\Reco4PHP\Config\Config $config
     */
    public function __construct(Node $input, Config $config = null)
    {
        $this->input = $input;
        $this->config = null !== $config ? $config : new SimpleConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function input()
    {
        return $this->input;
    }

    /**
     * {@inheritdoc}
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function timeLeft()
    {
        // TODO: Implement timeLeft() method.
    }
}
