<?php

namespace GraphAware\Reco4PHP\Post;

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Result\Recommendation;

interface PostProcessor
{
    public function name();

    public function postProcess(NodeInterface $input, Recommendation $recommendation);
}