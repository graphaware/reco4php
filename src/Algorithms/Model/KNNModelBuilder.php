<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Algorithms\Model;

use GraphAware\Reco4PHP\Algorithms\Similarity\Similarity;
use GraphAware\Reco4PHP\Common\ObjectSet;

class KNNModelBuilder
{
    protected Similarity $similarityFunction;

    public function __construct(Similarity $similarityFunction)
    {
        $this->similarityFunction = $similarityFunction;
    }

    public function computeSimilarity(ObjectSet $tfSource, ObjectSet $tfDestination): float
    {
        $vectors = $this->createVectors($tfSource, $tfDestination);

        return $this->similarityFunction->getSimilarity($vectors[0], $vectors[1]);
    }

    public function createVectors(ObjectSet $tfSource, ObjectSet $tfDestination): array
    {
        $ratings = [];
        foreach ($tfSource->getAll() as $source) {
            /* @var \GraphAware\Reco4PHP\Algorithms\Model\Rating $source */
            $ratings[$source->getId()][0] = $source->getRating();
        }

        foreach ($tfDestination->getAll() as $dest) {
            /* @var \GraphAware\Reco4PHP\Algorithms\Model\Rating $dest */
            $ratings[$dest->getId()][1] = $dest->getRating();
        }
        ksort($ratings);

        $xVector = [];
        $yVector = [];

        foreach ($ratings as $k => $rating) {
            $xVector[] = array_key_exists(0, $ratings[$k]) ? $ratings[$k][0] : 0;
            $yVector[] = array_key_exists(1, $ratings[$k]) ? $ratings[$k][1] : 0;
        }

        return [$xVector, $yVector];
    }
}
