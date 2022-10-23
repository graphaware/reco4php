<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Reco4PHP\Result\Recommendations;

class ExampleRecommenderService
{
    protected RecommenderService $service;

    /**
     * ExampleRecommenderService constructor.
     */
    public function __construct(string $databaseUri)
    {
        $this->service = RecommenderService::create($databaseUri);
        $this->service->registerRecommendationEngine(new ExampleRecommendationEngine());
    }

    public function recommendMovieForUserWithId(int $id): Recommendations
    {
        $input = $this->service->findInputBy('User', 'id', $id);
        $recommendationEngine = $this->service->getRecommender('user_movie_reco');

        return $recommendationEngine->recommend($input, new SimpleContext());
    }
}
