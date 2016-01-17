<?php

namespace GraphAware\Reco4PHP\Post;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Neo4j\Client\Result;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Transactional\BaseCypherAware;

abstract class CypherAwarePostProcessor extends BaseCypherAware implements PostProcessor
{
    final public function postProcess(NodeInterface $input, Recommendation $recommendation)
    {
        return;
    }

    abstract public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result);
}