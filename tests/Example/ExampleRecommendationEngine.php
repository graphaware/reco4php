<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use GraphAware\Reco4PHP\Tests\Example\Filter\AlreadyRatedBlackList;
use GraphAware\Reco4PHP\Tests\Example\Filter\ExcludeOldMovies;
use GraphAware\Reco4PHP\Tests\Example\PostProcessing\RewardWellRated;
use GraphAware\Reco4PHP\Tests\Example\Discovery\RatedByOthers;

class ExampleRecommendationEngine extends BaseRecommendationEngine
{
    public function name()
    {
        return "user_movie_reco";
    }

    public function engines()
    {
        return array(
            new RatedByOthers()
        );
    }

    public function blacklistBuilders()
    {
        return array(
            new AlreadyRatedBlackList()
        );
    }

    public function postProcessors()
    {
        return array(
            new RewardWellRated()
        );
    }

    public function filters()
    {
        return array(
            new ExcludeOldMovies()
        );
    }

    public function loggers()
    {
        return array();
    }


}