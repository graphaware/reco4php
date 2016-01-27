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
use GraphAware\Common\Type\NodeInterface;

abstract class BaseBlacklistBuilder implements BlackListBuilder
{
    /**
     * @param \GraphAware\Common\Result\Result $result
     *
     * @return \GraphAware\Common\Type\NodeInterface[]
     */
    public function buildBlackList(Result $result)
    {
        $nodes = [];
        foreach ($result->records() as $record) {
            if ($record->hasValue($this->itemResultName()) && $record->value($this->itemResultName() instanceof NodeInterface)) {
                $nodes[] = $record->value($this->itemResultName());
            }
        }

        return $nodes;
    }

    public function itemResultName()
    {
        return 'item';
    }
}
