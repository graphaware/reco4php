<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use GraphAware\Reco4PHP\Tests\Example\Discovery\FromSameGenreILike;
use GraphAware\Reco4PHP\Tests\Example\Discovery\RatedByOthers;
use GraphAware\Reco4PHP\Tests\Example\Filter\AlreadyRatedBlackList;
use GraphAware\Reco4PHP\Tests\Example\Filter\ExcludeOldMovies;
use GraphAware\Reco4PHP\Tests\Example\PostProcessing\RewardWellRated;

class ExampleRecommendationEngine extends BaseRecommendationEngine
{
    public function name(): string
    {
        return 'user_movie_reco';
    }

    public function discoveryEngines(): array
    {
        return [
            new RatedByOthers(),
            new FromSameGenreILike(),
        ];
    }

    public function blacklistBuilders(): array
    {
        return [
            new AlreadyRatedBlackList(),
        ];
    }

    public function postProcessors(): array
    {
        return [
            new RewardWellRated(),
        ];
    }

    public function filters(): array
    {
        return [
            new ExcludeOldMovies(),
        ];
    }
}
