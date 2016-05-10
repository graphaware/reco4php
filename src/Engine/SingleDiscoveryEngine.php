<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Reco4PHP\Engine;

use GraphAware\Common\Result\Record;
use GraphAware\Common\Result\ResultCollection;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\SingleScore;

abstract class SingleDiscoveryEngine implements DiscoveryEngine
{
    /**
     * @inheritdoc
     *
     * @param \GraphAware\Common\Type\Node $input
     * @param \GraphAware\Common\Type\Node $item
     * @param \GraphAware\Common\Result\Record $record
     * @return \GraphAware\Reco4PHP\Result\SingleScore
     */
    public function buildScore(Node $input, Node $item, Record $record)
    {
        $score = $record->hasValue($this->scoreResultName()) ? $record->value($this->scoreResultName()) : $this->defaultScore();
        $reason = $record->hasValue($this->reasonResultName()) ? $record->value($this->reasonResultName()) : null;

        return new SingleScore($score, $reason);
    }

    /**
     * @inheritdoc
     *
     * @param \GraphAware\Common\Type\Node $input
     * @param \GraphAware\Common\Result\ResultCollection $resultCollection
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    final public function produceRecommendations(Node $input, ResultCollection $resultCollection)
    {
        $result = $resultCollection->get($this->name());
        $recommendations = new Recommendations($this->name());

        foreach ($result->records() as $record) {
            if ($record->hasValue($this->recoResultName())) {
                $recommendations->add($record->value($this->recoResultName()), $this->name(), $this->buildScore($input, $record->value($this->recoResultName()), $record));
            }
        }

        return $recommendations;
    }

    /**
     * @inheritdoc
     */
    public function recoResultName()
    {
        return 'reco';
    }

    /**
     * @inheritdoc
     */
    public function scoreResultName()
    {
        return 'score';
    }

    /**
     * @inheritdoc
     */
    public function reasonResultName()
    {
        return 'reason';
    }

    /**
     * @inheritdoc
     */
    public function defaultScore()
    {
        return 1.0;
    }
}
