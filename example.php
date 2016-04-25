<?php

require_once __DIR__.'/vendor/autoload.php';

use GraphAware\Reco4PHP\Demo\Github\RecommendationEngine;
use GraphAware\Reco4PHP\RecommenderService;

$rs = RecommenderService::create("http://localhost:7474");
$rs->registerRecommendationEngine(new RecommendationEngine());

$stopwatch = new \Symfony\Component\Stopwatch\Stopwatch();

$input = $rs->findInputBy('User', 'login', 'jakzal');

$engine = $rs->getRecommender("github_who_to_follow");

$stopwatch->start('reco');
$recommendations = $engine->recommend($input);
$e = $stopwatch->stop('reco');

//echo $recommendations->size() . ' found in ' . $e->getDuration() .  'ms' .PHP_EOL;

foreach ($recommendations->getItems(10) as $reco) {
    echo $reco->item()->get('login') . PHP_EOL;
    echo $reco->totalScore() . PHP_EOL;
    foreach ($reco->getScores() as $name => $score) {
        echo "\t" . $name . ':' . $score->score() . PHP_EOL;
    }
}