<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Filter;

use GraphAware\Common\Result\Result;
use GraphAware\Common\Type\Node;

interface BlackListBuilder
{
    /**
     * @param \GraphAware\Common\Type\Node $input
     *
     * @return \GraphAware\Common\Cypher\Statement
     */
    public function blacklistQuery(Node $input);

    /**
     * @param \GraphAware\Common\Result\Result
     *
     * @return \GraphAware\Common\Type\Node[]
     */
    public function buildBlackList(Result $result);

    /**
     * @return string
     */
    public function itemResultName();

    /**
     * @return string
     */
    public function name();
}
