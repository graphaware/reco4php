<?php

/**
 * This file is part of the GraphAware Neo4j Common package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Result;

use InvalidArgumentException;
use Laudis\Neo4j\Types\CypherList;

class ResultCollection
{
    /**
     * @var CypherList[]
     */
    protected $resultsMap = [];

    public function add(CypherList $results, string $tag)
    {
        $this->resultsMap[$tag] = $results;
    }

    /**
     * @param mixed $default
     *
     * @throws InvalidArgumentException
     */
    public function get(string $tag, mixed $default = null): CypherList
    {
        if (array_key_exists($tag, $this->resultsMap)) {
            return $this->resultsMap[$tag];
        }

        if (2 === func_num_args()) {
            return $default;
        }

        throw new InvalidArgumentException(sprintf('This result collection does not contains a results for tag "%s"', $tag));
    }
}
