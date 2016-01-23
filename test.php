<?php

require_once __DIR__.'/vendor/autoload.php';

use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Reco4PHP\Tests\Demo\DummyEngine;

$dbService = new DatabaseService("bolt://localhost");
$dbService = new DatabaseService("http://neo4j:error!2101CWX@octify-neo4j:7487");
//$dbService = new DatabaseService("http://neo4j:error!2101CWX@octify-neo4j:7487");
$recommender = new RecommenderService($dbService);
$recommender->registerRecommendationEngine(new DummyEngine());

$result = $dbService->getDriver()->run("MATCH (n:User {login: {login} }) RETURN n", ['login' => 'jeremykendall']);
$input = $result->getRecord()->value("n");

if ($input) {
    $s = microtime(true);
    $i=0;
    $recommendations = $recommender->getRecommender("dummy")->recommend($input);
    $recommendations->sort();
    echo 'number of recommendations are : ' . $recommendations->size() . PHP_EOL;
    echo 'recommendations computed in :' . (microtime(true) - $s) . PHP_EOL;
    $items = $recommendations->getItems();
    foreach ($items as $reco) {
        //echo $reco->item()->value('login') . PHP_EOL;
        //echo count($reco->getScores()) . PHP_EOL;
        //echo $reco->totalScore() . PHP_EOL;
        print_r($reco);
        ++$i;
        if ($i > 2) {
            exit();
        }
    }
    //print_r($items);
}