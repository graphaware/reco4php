<?php

namespace GraphAware\Reco4PHP\Tests\Integration;

use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Reco4PHP\Tests\Integration\Model\RecoEngine;
use Laudis\Neo4j\Types\Node;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleFriendsRecoEngineTest.
 *
 * @group integration
 */
class SimpleFriendsRecoEngineTest extends TestCase
{
    protected RecommenderService $recoService;

    protected DatabaseService $databaseService;

    /**
     * @setUp()
     */
    public function setUp(): void
    {
        $this->databaseService = new DatabaseService('bolt://localhost:7687');
        $this->recoService = new RecommenderService($this->databaseService);
        $this->recoService->registerRecommendationEngine(new RecoEngine());
        $this->createGraph();
    }

    public function testRecoForJohn(): void
    {
        $engine = $this->recoService->getRecommender('find_friends');
        $john = $this->getUserNode('John');
        $recommendations = $engine->recommend($john, new SimpleContext());
        $recommendations->sort();
        $this->assertEquals(2, $recommendations->size());
        $this->assertNull($recommendations->getItemBy('name', 'John'));
        $recoForMarc = $recommendations->getItemBy('name', 'marc');
        $this->assertEquals(1, $recoForMarc->totalScore());
        $recoForBill = $recommendations->getItemBy('name', 'Bill');
        $this->assertEquals(2, $recoForBill->totalScore());
    }

    private function getUserNode(string $name): Node
    {
        $q = 'MATCH (n:User) WHERE n.name = $name RETURN n';
        $results = $this->databaseService->getDriver()->run($q, ['name' => $name]);

        return $results->first()->get('n');
    }

    private function createGraph(): void
    {
        $this->databaseService->getDriver()->run('MATCH (n) DETACH DELETE n');
        $query = 'CREATE (john:User {name:"John"})-[:FRIEND]->(judith:User {name:"Judith"}),
        (john)-[:FRIEND]->(paul:User {name:"paul"}),
        (paul)-[:FRIEND]->(marc:User {name:"marc"}),
        (paul)-[:FRIEND]->(bill:User {name:"Bill"}),
        (judith)-[:FRIEND]->(bill),
        (judith)-[:FRIEND]->(sofia),
        (john)-[:FRIEND]->(sofia),
        (sofia)-[:FRIEND]->(:User {name:"Zoe"})';
        $this->databaseService->getDriver()->run($query);
    }
}
