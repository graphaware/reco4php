<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Reco4PHP\Filter\Filter;
use Laudis\Neo4j\Types\Node;

class ExcludeOldMovies implements Filter
{
    public function doInclude(Node $input, Node $item): bool
    {
        $title = (string) $item->getProperty('title');
        preg_match('/(?:\()\d+(?:\))/', $title, $matches);

        if (isset($matches[0])) {
            $y = str_replace('(', '', $matches[0]);
            $y = str_replace(')', '', $y);
            $year = (int) $y;
            if ($year < 1999) {
                return false;
            }

            return true;
        }

        return false;
    }
}
