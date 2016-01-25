<?php

namespace GraphAware\Reco4PHP\Tests\Algorithms\Model;

use GraphAware\Reco4PHP\Algorithms\Similarity\CosineSimilarity;
use GraphAware\Reco4PHP\Common\ObjectSet;
use GraphAware\Reco4PHP\Algorithms\Model\Rating;
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;
use GraphAware\Reco4PHP\Algorithms\Model\KNNModelBuilder;

/**
 * @group algorithms
 * @group knn
 */
class KNNModelBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateVectors()
    {
        $instance = new KNNModelBuilder();
        $source = new ObjectSet(Rating::class);
        $destination = new ObjectSet(Rating::class);
        $node1 = new FakeNode(1);
        $node2 = new FakeNode(2);
        $node3 = new FakeNode(3);
        $node4 = new FakeNode(4);

        $source->add(new Rating(1, $node1->identity()));
        $source->add(new Rating(1, $node3->identity()));

        $destination->add(new Rating(1, $node2->identity()));
        $destination->add(new Rating(1, $node4->identity()));

        $vectors = $instance->createVectors($source, $destination);

        $xVector = $vectors[0];
        $yVector = $vectors[1];

        $this->assertEquals(array(1,0,1,0), $xVector);
        $this->assertEquals(array(0,1,0,1), $yVector);
    }

    public function testComputeSimilarity()
    {
        $instance = new KNNModelBuilder(null, new CosineSimilarity());
        $source = new ObjectSet(Rating::class);
        $destination = new ObjectSet(Rating::class);
        $node1 = new FakeNode(1);
        $node2 = new FakeNode(2);
        $node3 = new FakeNode(3);
        $node4 = new FakeNode(4);

        $source->add(new Rating(1, $node1->identity()));
        $source->add(new Rating(1, $node3->identity()));

        $destination->add(new Rating(1, $node2->identity()));
        $destination->add(new Rating(1, $node4->identity()));

        $similarity = $instance->computeSimilarity($source, $destination);
        $this->assertEquals(0.0, $similarity);
    }

    public function testComputeSimilarity2()
    {
        $instance = new KNNModelBuilder(null, new CosineSimilarity());
        $source = new ObjectSet(Rating::class);
        $destination = new ObjectSet(Rating::class);
        $node1 = new FakeNode(1);
        $node2 = new FakeNode(2);
        $node3 = new FakeNode(3);
        $node4 = new FakeNode(4);
        $node5 = new FakeNode(5);

        $source->add(new Rating(1, $node1->identity()));
        $source->add(new Rating(3, $node4->identity()));

        $destination->add(new Rating(1, $node2->identity()));
        $destination->add(new Rating(2, $node4->identity()));
        $destination->add(new Rating(5, $node5->identity()));

        $similarity = $instance->computeSimilarity($source, $destination);
        $this->assertTrue($similarity >= 0.34641016 && $similarity <= 0.346410161514);
    }
}