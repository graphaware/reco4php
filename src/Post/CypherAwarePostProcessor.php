<?php

namespace GraphAware\Reco4PHP\Post;

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Transactional\BaseCypherAware;
use GraphAware\Reco4PHP\Transactional\CypherAwareSingleRecommendationProcessor;

abstract class CypherAwarePostProcessor extends BaseCypherAware implements PostProcessor, CypherAwareSingleRecommendationProcessor
{
    public function postProcess(NodeInterface $input, Recommendation $recommendation)
    {
        return;
    }
}