<?php

require_once __DIR__.'/vendor/autoload.php';

use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Reco4PHP\Tests\Demo\DummyEngine;

$recommender = new \GraphAware\Reco4PHP\Tests\Example\ExampleRecommenderService("bolt://localhost");

$recommendations = $recommender->recommendMovieForUserWithId('460');

print_r($recommendations->getItems(3));