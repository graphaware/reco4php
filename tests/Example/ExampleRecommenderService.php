<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\RecommenderService;

class ExampleRecommenderService
{
    /**
     * @var \GraphAware\Reco4PHP\RecommenderService
     */
    protected $service;

    /**
     * ExampleRecommenderService constructor.
     * @param string $databaseUri
     */
    public function __construct($databaseUri)
    {
        $this->service = RecommenderService::create($databaseUri);
        $this->service->registerRecommendationEngine(new ExampleRecommendationEngine());
    }

    /**
     * @param int $id
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    public function recommendMovieForUserWithId($id)
    {
        $input = $this->service->findInputById($id);
        $recommendationEngine = $this->service->getRecommender("user_movie_reco");

        return $recommendationEngine->recommend($input);
    }
}