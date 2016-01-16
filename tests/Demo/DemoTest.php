<?php

namespace GraphAware\Reco4PHP\Tests\Demo;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\RecommenderService;

/**
 * @group demo
 */
class DemoTest extends \PHPUnit_Framework_TestCase
{
    public function testDemo()
    {
        $dbService = new DatabaseService("http://neo4j:error!2101CWX@gitbeat:7474");
        $recommender = new RecommenderService($dbService);
        $recommender->registerRecommendationEngine(new DummyEngine());

        $result = $dbService->getDriver()->run("MATCH (n:User {login: {login} }) RETURN n", ['login' => 'ikwattro']);
        $input = $result->getRecord()->value("n");

        if ($input) {
            $s = microtime(true);
            $recommendations = $recommender->getRecommender("dummy")->recommend($input);
            echo 'number of recommendations are : ' . $recommendations->size() . PHP_EOL;
            echo microtime(true) - $s . PHP_EOL;
        }
    }
}