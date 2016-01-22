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

use GraphAware\Common\Result\RecordViewInterface;
use GraphAware\Common\Result\ResultCollection;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\SingleScore;
use GraphAware\Reco4PHP\Transactional\BaseCypherAware;

abstract class SingleDiscoveryEngine extends BaseCypherAware implements DiscoveryEngine
{

    public function buildScore(NodeInterface $input, NodeInterface $item, RecordViewInterface $record)
    {
        $score = $record->hasValue($this->scoreResultName()) ? $record->value($this->scoreResultName()): $this->defaultScore();
        $reason = $record->hasValue($this->reasonResultName()) ? $record->value($this->reasonResultName()): null;

        return new SingleScore($score, $reason);
    }

    final public function produceRecommendations(NodeInterface $input, ResultCollection $resultCollection)
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


    public function idParamName()
    {
        return "inputId";
    }

    public function recoResultName()
    {
        return "reco";
    }

    public function scoreResultName()
    {
        return "score";
    }

    public function reasonResultName()
    {
        return "reason";
    }

    public function defaultScore()
    {
        return 1.0;
    }

    final public function buildParams(NodeInterface $input)
    {
        $this->query();
        $this->addParameter($this->idParamName(), $input->identity());
    }
}