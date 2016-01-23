<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Filter\Filter;

class ExcludeOldMovies implements Filter
{
    public function doInclude(NodeInterface $input, NodeInterface $item)
    {
        $title = $item->value("title");
        preg_match('/(?:\()\d+(?:\))/', $title, $matches);

        if (isset($matches[1])) {
            $year = (int) $matches[1];
            if ($year < 1999) {
                return false;
            }

            return true;
        }

        return false;
    }

}