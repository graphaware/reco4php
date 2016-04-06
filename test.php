<?php

require_once __DIR__.'/vendor/autoload.php';

$rs = \GraphAware\Reco4PHP\RecommenderService::create("http://localhost:7474");
$rs->registerRecommendationEngine(new \GraphAware\Reco4PHP\Tests\Example\ExampleRecommendationEngine());

$stopwatch = new \Symfony\Component\Stopwatch\Stopwatch();

$input = $rs->findInputBy('User', 'id', 460);

$engine = $rs->getRecommender("example");

$stopwatch->start('reco');
$recommendations = $engine->recommend($input);
$e = $stopwatch->stop('reco');

echo $recommendations->size() . ' found in ' . $e->getDuration() .  'ms' .PHP_EOL;

print_r($recommendations->getItems(10));