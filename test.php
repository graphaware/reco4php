<?php

require_once __DIR__.'/vendor/autoload.php';

use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Reco4PHP\Tests\Demo\DummyEngine;

$dbService = new DatabaseService("bolt://localhost");
$recommender = new RecommenderService($dbService);
$recommender->registerRecommendationEngine(new DummyEngine());

$result = $dbService->getDriver()->run("MATCH (n:User {login: {login} }) RETURN n", ['login' => 'jakzal']);
$input = $result->getRecord()->value("n");

if ($input) {
    $s = microtime(true);
    $recommendations = $recommender->getRecommender("dummy")->recommend($input);
    //$recommendations->sort();
    echo 'number of recommendations are : ' . $recommendations->size() . PHP_EOL;
    echo 'recommendations computed in :' . (microtime(true) - $s) . PHP_EOL;
    $items = $recommendations->getItems();

    $i = 0;

    foreach ($recommendations->getItems() as $recommendation) {
        if (count($recommendation->scores()) > 0) {
            //print_r($recommendation);
            ++$i;
            if ($i > 9) {
                break;
            }
        }
    }
}