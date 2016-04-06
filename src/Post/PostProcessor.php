<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Post;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Result\Recommendation;

interface PostProcessor
{
    public function name();
}
