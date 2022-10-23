<?php

namespace GraphAware\Reco4PHP\Tests\Algorithms\Model;

use GraphAware\Reco4PHP\Algorithms\Model\KNNModelBuilder;
use GraphAware\Reco4PHP\Algorithms\Model\Rating;
use GraphAware\Reco4PHP\Algorithms\Similarity\CosineSimilarity;
use GraphAware\Reco4PHP\Common\ObjectSet;
use GraphAware\Reco4PHP\Tests\Helper\FakeNode;
use PHPUnit\Framework\TestCase;

/**
 * @group algorithms
 * @group knn
 */
class KNNModelBuilderTest extends TestCase
{
    public function testCreateVectors(): void
    {
        $instance = new KNNModelBuilder(new CosineSimilarity());
        $source = new ObjectSet(Rating::class);
        $destination = new ObjectSet(Rating::class);
        $node1 = FakeNode::createDummy(1);
        $node2 = FakeNode::createDummy(2);
        $node3 = FakeNode::createDummy(3);
        $node4 = FakeNode::createDummy(4);

        $source->add(new Rating(1, $node1->getId()));
        $source->add(new Rating(1, $node3->getId()));

        $destination->add(new Rating(1, $node2->getId()));
        $destination->add(new Rating(1, $node4->getId()));

        $vectors = $instance->createVectors($source, $destination);

        $xVector = $vectors[0];
        $yVector = $vectors[1];

        $this->assertEquals([1, 0, 1, 0], $xVector);
        $this->assertEquals([0, 1, 0, 1], $yVector);
    }

    public function testComputeSimilarity(): void
    {
        $instance = new KNNModelBuilder(new CosineSimilarity());
        $source = new ObjectSet(Rating::class);
        $destination = new ObjectSet(Rating::class);
        $node1 = FakeNode::createDummy(1);
        $node2 = FakeNode::createDummy(2);
        $node3 = FakeNode::createDummy(3);
        $node4 = FakeNode::createDummy(4);

        $source->add(new Rating(1, $node1->getId()));
        $source->add(new Rating(1, $node3->getId()));

        $destination->add(new Rating(1, $node2->getId()));
        $destination->add(new Rating(1, $node4->getId()));

        $similarity = $instance->computeSimilarity($source, $destination);
        $this->assertEquals(0.0, $similarity);
    }

    public function testComputeSimilarity2(): void
    {
        $instance = new KNNModelBuilder(new CosineSimilarity());
        $source = new ObjectSet(Rating::class);
        $destination = new ObjectSet(Rating::class);
        $node1 = FakeNode::createDummy(1);
        $node2 = FakeNode::createDummy(2);
        $node3 = FakeNode::createDummy(3);
        $node4 = FakeNode::createDummy(4);
        $node5 = FakeNode::createDummy(5);

        $source->add(new Rating(1, $node1->getId()));
        $source->add(new Rating(3, $node4->getId()));

        $destination->add(new Rating(1, $node2->getId()));
        $destination->add(new Rating(2, $node4->getId()));
        $destination->add(new Rating(5, $node5->getId()));

        $similarity = $instance->computeSimilarity($source, $destination);
        $this->assertTrue($similarity >= 0.34641016 && $similarity <= 0.346410161514);
    }
}
