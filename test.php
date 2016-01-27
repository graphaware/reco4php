<?php

require_once __DIR__.'/vendor/autoload.php';

$rs = \GraphAware\Reco4PHP\RecommenderService::create("http://neo4j:error@localhost:7474");
$rs->registerRecommendationEngine(new \GraphAware\Reco4PHP\Tests\Example\ExampleRecommendationEngine());

$input = $rs->findInputBy('User', 'id', 460);

$engine = $rs->getRecommender("example");

$recommendations = $engine->recommend($input);

echo $recommendations->size();