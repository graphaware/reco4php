<?php

namespace GraphAware\Reco4PHP\Tests\Integration;

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\Tests\Integration\Model\RecoEngine;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Neo4j\Client\Client;

/**
 * Class SimpleFriendsRecoEngineTest
 * @package GraphAware\Reco4PHP\Tests\Integration
 *
 * @group integration
 */
class SimpleFriendsRecoEngineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RecommenderService
     */
    protected $recoService;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @setUp()
     */
    public function setUp()
    {
        $this->recoService = RecommenderService::create('http://localhost:7474');
        $this->recoService->registerRecommendationEngine(new RecoEngine());
        $this->client = ClientBuilder::create()
            ->addConnection('default', 'http://localhost:7474')
            ->build();
        $this->createGraph();
    }

    public function testRecoForJohn()
    {
        $engine = $this->recoService->getRecommender('find_friends');
        $john = $this->getUserNode('John');
        $recommendations = $engine->recommend($john, new SimpleContext());
        $recommendations->sort();
        $this->assertEquals(2, $recommendations->size());
        $this->assertNull($recommendations->getItemBy('name', 'John'));
        $recoForMarc = $recommendations->getItemBy('name','marc');
        $this->assertEquals(1, $recoForMarc->totalScore());
        $recoForBill = $recommendations->getItemBy('name', 'Bill');
        $this->assertEquals(2, $recoForBill->totalScore());
    }

    private function getUserNode($name)
    {
        $q = 'MATCH (n:User) WHERE n.name = {name} RETURN n';
        $result = $this->client->run($q, ['name' => $name]);

        return $result->firstRecord()->get('n');
    }

    private function createGraph()
    {
        $this->client->run('MATCH (n) DETACH DELETE n');
        $query = 'CREATE (john:User {name:"John"})-[:FRIEND]->(judith:User {name:"Judith"}),
        (john)-[:FRIEND]->(paul:User {name:"paul"}),
        (paul)-[:FRIEND]->(marc:User {name:"marc"}),
        (paul)-[:FRIEND]->(bill:User {name:"Bill"}),
        (judith)-[:FRIEND]->(bill),
        (judith)-[:FRIEND]->(sofia),
        (john)-[:FRIEND]->(sofia),
        (sofia)-[:FRIEND]->(:User {name:"Zoe"})';
        $this->client->run($query);
    }
}