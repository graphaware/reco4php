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
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\SingleScore;

abstract class SingleDiscoveryEngine implements DiscoveryEngine
{
    private static $DEFAULT_RECO_NAME = 'reco';
    private static $DEFAULT_SCORE_NAME = 'score';
    private static $DEFAULT_REASON_NAME = 'reason';

    /**
     * {@inheritdoc}
     *
     * @param Node                  $input
     * @param Node                  $item
     * @param Record $record
     * @param Context $context
     *
     * @return \GraphAware\Reco4PHP\Result\SingleScore
     */
    public function buildScore(Node $input, Node $item, Record $record, Context $context)
    {
        $score = $record->hasValue($this->scoreResultName()) ? $record->value($this->scoreResultName()) : $this->defaultScore();
        $reason = $record->hasValue($this->reasonResultName()) ? $record->value($this->reasonResultName()) : null;

        return new SingleScore($score, $reason);
    }

    /**
     * {@inheritdoc}
     *
     * @param Node               $input
     * @param ResultCollection $resultCollection
     * @param Context $context
     *
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    final public function produceRecommendations(Node $input, ResultCollection $resultCollection, Context $context)
    {
        $result = $resultCollection->get($this->name());
        $recommendations = new Recommendations($this->name());

        foreach ($result->records() as $record) {
            if ($record->hasValue($this->recoResultName())) {
                $recommendations->add($record->value($this->recoResultName()), $this->name(), $this->buildScore($input, $record->value($this->recoResultName()), $record, $context));
            }
        }

        return $recommendations;
    }

    /**
     * {@inheritdoc}
     */
    public function recoResultName()
    {
        return self::$DEFAULT_RECO_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function scoreResultName()
    {
        return self::$DEFAULT_SCORE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function reasonResultName()
    {
        return self::$DEFAULT_REASON_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultScore()
    {
        return 1.0;
    }
}
