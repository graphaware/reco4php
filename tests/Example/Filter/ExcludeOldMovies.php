<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Filter\Filter;

class ExcludeOldMovies implements Filter
{
    public function doInclude(Node $input, Node $item)
    {
        $title = $item->value("title");
        preg_match('/(?:\()\d+(?:\))/', $title, $matches);

        if (isset($matches[0])) {
            $y = str_replace('(','',$matches[0]);
            $y = str_replace(')','', $y);
            $year = (int) $y;
            if ($year < 1999) {
                return false;
            }

            return true;
        }

        return false;
    }

}