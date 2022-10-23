<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Result;

class SingleScore
{
    private float $score;

    private ?string $reason;

    /**
     * SingleScore constructor.
     */
    public function __construct(float $score, ?string $reason = null)
    {
        $this->score = $score;
        $this->addReason($reason);
    }

    public function addReason(?string $reason = null): void
    {
        if (null !== $reason) {
            $this->reason = $reason;
        }
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
