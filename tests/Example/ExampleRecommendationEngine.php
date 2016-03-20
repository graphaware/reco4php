<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use GraphAware\Reco4PHP\Tests\Example\Discovery\FromSameGenreILike;
use GraphAware\Reco4PHP\Tests\Example\Filter\AlreadyRatedBlackList;
use GraphAware\Reco4PHP\Tests\Example\Filter\ExcludeOldMovies;
use GraphAware\Reco4PHP\Tests\Example\PostProcessing\RewardWellRated;
use GraphAware\Reco4PHP\Tests\Example\Discovery\RatedByOthers;

class ExampleRecommendationEngine extends BaseRecommendationEngine
{
    public function name()
    {
        return "example";
    }

    public function discoveryEngines()
    {
        return array(
            new RatedByOthers(),
            new FromSameGenreILike()
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
}