<?php

require_once __DIR__.'/../vendor/autoload.php';

use GraphAware\Reco4PHP\Persistence\DatabaseService;
use GraphAware\Reco4PHP\Algorithms\Model\KNNModelBuilder;
use GraphAware\Reco4PHP\Algorithms\Model\Rating;
use GraphAware\Reco4PHP\Common\ObjectSet;
use GraphAware\Reco4PHP\Algorithms\Similarity\CosineSimilarity;
use Symfony\Component\Stopwatch\Stopwatch;

$db = new DatabaseService("http://neo4j:error@localhost:7474");
$driver = $db->getDriver();
$knn = new KNNModelBuilder(null, new CosineSimilarity());
$s = microtime(true);


$qA = "MATCH (m:Movie) OPTIONAL MATCH (m)<-[r:RATED]-(u)
RETURN id(m) as m, collect({rating: r.rating, user: id(u)}) as ratings LIMIT 1500";
$stopwatch = new Stopwatch();
$stopwatch->start("e");
$result = $driver->run($qA);
$e = $stopwatch->stop("e");
echo $e->getDuration() . PHP_EOL;

$stopwatch->start("simil");
$pairs = [];

$crs = array_chunk($result->records(), 100);

foreach ($result->records() as $record) {
    $source = new ObjectSet(Rating::class);
    $m = $record->value("m");
    foreach ($record->value("ratings") as $rating) {
        $source->add(new Rating($rating['rating'], $rating['user']));
    }
    foreach ($crs as $cr) {
        foreach ($cr as $record2) {
            $m2 = $record2->value("m");
            $k = $m + $m2;
            if (!array_key_exists($k, $pairs) && $record2->value("m") !== $record->value("m")) {
                $destination = new ObjectSet(Rating::class);
                foreach ($record2->value("ratings") as $rating2) {
                    $destination->add(new Rating($rating2['rating'], $rating2['user']));
                }
                $simil = $knn->computeSimilarity($source, $destination);
                $pairs[$k] = [
                    'source' => $record->value("m"),
                    'desc' => $record2->value("m"),
                    'similarity' => $simil
                ];
                //echo $simil . PHP_EOL;
            }
        }
    }
}
$e2 = $stopwatch->stop("simil");
echo $e2->getDuration() . PHP_EOL;