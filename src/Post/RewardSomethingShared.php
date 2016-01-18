<?php

/**
 * This file is part of the GraphAware Reco4PHP package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Reco4PHP\Post;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Graph\Direction;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Score;

abstract class RewardSomethingShared extends CypherAwarePostProcessor
{
    abstract public function relationshipType();

    public function relationshipDirection()
    {
        return Direction::BOTH;
    }

    final public function query()
    {
        $relationshipPatterns = [
            Direction::BOTH => array('-[:%s]-','-[:%s]-'),
            Direction::INCOMING => array('<-[:%s]-','-[:%s]->'),
            Direction::OUTGOING => array('-[:%s]->', '<-[:%s]-')
        ];

        $relPattern = sprintf($relationshipPatterns[$this->relationshipDirection()][0], $this->relationshipType());
        $inversedRelPattern = sprintf($relationshipPatterns[$this->relationshipDirection()][1], $this->relationshipType());

        $query = 'MATCH (input)' . $relPattern . '(shared)' . $inversedRelPattern . '(output)
        RETURN shared as sharedThing';

        return $query;
    }

    public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result)
    {
        if (count($result->records()) > 0) {
            foreach ($result->records() as $record) {
                $recommendation->addScore(new Score(1, $this->name()));
            }
        }
    }

}