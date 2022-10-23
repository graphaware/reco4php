<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Algorithms\Similarity;

class CosineSimilarity implements Similarity
{
    public function getSimilarity(array $xVector, array $yVector): float
    {
        $a = $this->getDotProduct($xVector, $yVector);
        $b = $this->getNorm($xVector) * $this->getNorm($yVector);

        if ($b > 0) {
            return $a / $b;
        }

        return 0;
    }

    private function getDotProduct(array $xVector, array $yVector): float
    {
        $sum = 0.0;
        foreach ($xVector as $k => $v) {
            $sum += (float) ($xVector[$k] * $yVector[$k]);
        }

        return $sum;
    }

    private function getNorm(array $vector): float
    {
        $sum = 0.0;
        foreach ($vector as $k => $v) {
            $sum += (float) ($vector[$k] * $vector[$k]);
        }

        return sqrt($sum);
    }
}
