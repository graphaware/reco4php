# GraphAware Reco4PHP

## Neo4j based Recommendation Engine Framework for PHP

GraphAware Reco4PHP is a library for building complex recommendation engines atop Neo4j.

Features:

* Clean and flexible design
* Built-in algorithms and functions
* Ability to measure recommendation quality
* Built-in Cypher transaction management

Requirements:

* PHP5.6+ (PHP7 recommended)
* Neo4j 2.2.6+ (Neo4j 3.0.0M02 recommended)

The library imposes a specific recommendation engine architecture, which has emerged from our experience building recommendation
engines and solves the architectural challenge to run recommendation engines remotely via Cypher.
In return it handles all the plumbing so that you only write the recommendation business logic specific to your use case.

### Recommendation Engine Architecture

#### Discovery Engines and Recommendations

The purpose of a recommendation engine is to `recommend` something, should be users you should follow, products you should buy,
articles you should read.

The first part in the recommendation process is to find items to recommend, it is called the `discovery` process.

In Reco4PHP, a `DiscoveryEngine` is responsible for discovering items to recommend in one possible way.

Generally, recommender systems will contains multiple discovery engines, if you would write the `who you should follow on github` recommendation engine,
you might end up with the non-exhaustive list of `Discovery Engines` :

* Find people that contributed on the same repositories than me
* Find people that `FOLLOWS` the same people I follow
* Find people that `WATCH` the same repositories I'm watching
* ...

Each `Discovery Engine` will produce a set of `Recommendations` which contains the discovered `Item` as well as the score for this item (more below).

#### Filters and BlackLists

The purpose of `Filters` is to compare the original `input` to the `discovered` item and decide whether or not this item should be recommended to the user.
A very straightforward filter could be `ExcludeSelf` which would exclude the item if it is the same node as the input, which can relatively happen in a densely connected graph.

`BlackLists` on the other hand are a set of predefined nodes that should not be recommended to the user. An example could be to create a `BlackList` with the already purchased items
by the user if you would recommend him products he should buy.

#### PostProcessors

`PostProcessors` are providing the ability to post process the recommendation after it has passed the filters and blacklisting process.

For example, if you would reward a recommended person if he/she lives in the same city than you, it wouldn't make sense to load all people from the database that live
in this city in the discovery phase (this could be millions if you take London as an example).

You would then create a `RewardSameCity` post processor that would adapt the score of the produced recommendation if the input node and the recommended item are living in the same city.

#### Summary

To summarize, a typical recommendation engine will be a set of :

* one or more `Discovery Engines`
* zero or more `Fitlers` and `BlackLists`
* zero or more `PostProcessors`

Let's start it !


#### Usage by example

We will use the small dataset available from MovieLens containing movies, users and ratings as well as genres.

The dataset is publicly available here : http://grouplens.org/datasets/movielens/

Once downloaded and extracted the archive, you can run the following Cypher statements for importing the dataset, just adapt the file urls to match your actual path to the files :

```
CREATE CONSTRAINT ON (m:Movie) ASSERT m.id IS UNIQUE;
CREATE CONSTRAINT ON (g:Genre) ASSERT g.name IS UNIQUE;
CREATE CONSTRAINT ON (u:User) ASSERT u.id IS UNIQUE;
```

```
LOAD CSV WITH HEADERS FROM "file:///Users/ikwattro/dev/movielens/movies.csv" AS row
WITH row
MERGE (movie:Movie {id: toInt(row.movieId)})
ON CREATE SET movie.title = row.title
WITH movie, row
UNWIND split(row.genres, '|') as genre
MERGE (g:Genre {name: genre})
MERGE (movie)-[:HAS_GENRE]->(g)
```


```
USING PERIODIC COMMIT 500
LOAD CSV WITH HEADERS FROM "file:///Users/ikwattro/dev/movielens/ratings.csv" AS row
WITH row
MATCH (movie:Movie {id: toInt(row.movieId)})
MERGE (user:User {id: toInt(row.userId)})
MERGE (user)-[r:RATED]->(movie)
ON CREATE SET r.rating = toInt(row.rating), r.timestamp = toInt(row.timestamp)
```

For the purpose of the example, we will assume we are recommending movies for the User with ID 460.


### Installation

Require the dependency with `composer` :

```bash
composer require graphaware/reco4php
```

### Usage

#### Discovery

In order to recommend movies people should watch, you have decided that we should find potential recommendations in the following way :

* Find movies rated by people who rated the same movies than me, but that I didn't rated yet

As told before, the `reco4php` recommendation engine framework makes all the plumbing so you only have to concentrate on the business logic, that's why it provides base class that you should extend and just implement
the methods of the upper interfaces, here is how you would create your first discovery engine :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class RatedByOthers extends SingleDiscoveryEngine
{
    public function query()
    {
        $query = "MATCH (input)-[:RATED]->(m)<-[:RATED]-(other)
        WITH distinct other
        MATCH (other)-[r:RATED]->(reco)
        WITH distinct reco, sum(r.rating) as score
        ORDER BY score DESC
        RETURN reco, score LIMIT 500";

        return $query;
    }

    public function name()
    {
        return "rated_by_others";
    }

}
```

The `input` node is implicitly matched by the underlying query executor, so you don't have to write the query for matching the input node everytime. So basically it is doing for you `MATCH (input) WHERE id(input) = {idInput}`;

The `query` method should return a string containing the query for finding recommendations, the `name` method should return a string describing the name of your engine (this is mostly for logging purposes).

The query here has some logic, we don't want to return as candidates all the movies found, as in the initial dataset it would be 10k+, so imagine what it would be on a 100M dataset. So we are summing the score
of the ratings and returning the most rated ones, limit the results to 500 potential recommendations.


The base class assumes that the recommended node will have the identifier `reco` and the score of the produced recommendation the identifier `score`. The score is not mandatory, and it will be given a default score of `1`.

All these defaults are customizable by overriding the methods from the base class (see the Customization section).

This discovery engine will then produce a set of 500 scored `Recommendation` objects that you can use in your filters or post processors.

#### Filtering

As an example of a filter, we will filter the movies that were produced before the year 1999. The year is written in the movie title, so we will use a regex for extracting the year in the filter.

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Filter\Filter;

class ExcludeOldMovies implements Filter
{
    public function doInclude(NodeInterface $input, NodeInterface $item)
    {
        $title = $item->value("title");
        preg_match('/(?:\()\d+(?:\))/', $title, $matches);

        if (isset($matches[0])) {
            $y = str_replace('(','',$matches[0]);
            $y = str_replace(')','', $y);
            $year = (int) $y;
            if ($year < 1999) {
                return false;
            }

            return true;
        }

        return false;
    }

}
```

The `Filter` interfaces forces you to implement the `doInclude` method which should return a boolean. You have access to the recommended node as well as the input in the method arguments.

#### Blacklist

Of course we do not want to recommend movies that the current user has already rated, for this we will create a Blacklist building a set of these already rated movie nodes.

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\Filter;

use GraphAware\Reco4PHP\Filter\BaseBlackListBuilder;

class AlreadyRatedBlackList extends BaseBlackListBuilder
{
    public function query()
    {
        $query = "MATCH (input)-[:RATED]->(movie)
        RETURN movie as item";

        return $query;
    }

}
```

Again, the framework takes care of matching first the input node for you. You really just need to add the logic for matching the nodes that should be blacklisted, the framework takes care for filtering the recommended
nodes against the blacklists provided.

#### Post Processors

`Post Processors` are meant to add additional scoring to the recommended items. In our example, we could reward a produced recommendation if it has more than 10 ratings :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\PostProcessing;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Type\NodeInterface;
use GraphAware\Reco4PHP\Post\CypherAwarePostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\SingleScore;

class RewardWellRated extends CypherAwarePostProcessor
{
    public function query()
    {
        $query = "RETURN size((reco)<-[:RATED]-()) as ratings";

        return $query;
    }

    public function doPostProcess(NodeInterface $input, Recommendation $recommendation, RecordCursorInterface $result)
    {
        $record = $result->getRecord();
        if ($rating = $record->value("ratings")) {
            if ($rating > 10) {
                $recommendation->addScore($this->name(), new SingleScore($rating));
            }

        }
    }

    public function name()
    {
        return "reward_well_rated";
    }

}
```

#### Wiring all together

Now that our components are created, we need to build effectively our recommendation engine :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use GraphAware\Reco4PHP\Tests\Example\Filter\AlreadyRatedBlackList;
use GraphAware\Reco4PHP\Tests\Example\Filter\ExcludeOldMovies;
use GraphAware\Reco4PHP\Tests\Example\PostProcessing\RewardWellRated;

class ExampleRecommendationEngine extends BaseRecommendationEngine
{
    public function name()
    {
        return "user_movie_reco";
    }

    public function engines()
    {
        return array(
            new RatedByOthers()
        );
    }

    public function blacklistBuilders()
    {
        return array(
            new AlreadyRatedBlackList()
        );
    }

    public function postProcessors()
    {
        return array(
            new RewardWellRated()
        );
    }

    public function filters()
    {
        return array(
            new ExcludeOldMovies()
        );
    }

    public function loggers()
    {
        return array();
    }


}
```

As in your recommender service, you might have multiple recommendation engines serving different recommendations, the last step is to create this service and register each `RecommendationEngine` you have created.
You'll need to provide also a connection to your Neo4j database, in your application this could look like this :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\RecommenderService;

class ExampleRecommenderService
{
    /**
     * @var \GraphAware\Reco4PHP\RecommenderService
     */
    protected $service;

    /**
     * ExampleRecommenderService constructor.
     * @param string $databaseUri
     */
    public function __construct($databaseUri)
    {
        $this->service = RecommenderService::create($databaseUri);
        $this->service->registerRecommendationEngine(new ExampleRecommendationEngine());
    }

    /**
     * @param int $id
     * @return \GraphAware\Reco4PHP\Result\Recommendations
     */
    public function recommendMovieForUserWithId($id)
    {
        $input = $this->service->findInputBy('User', 'id', $id);
        $recommendationEngine = $this->service->getRecommender("user_movie_reco");

        return $recommendationEngine->recommend($input);
    }
}
```

#### Inspecting recommendations

The `recommend()` method on a recommendation engine will returns you a `Recommendations` object which contains a set of `Recommendation` that holds the recommended item and their score.

Each score is inserted so you can easily inspect why such recommendation has been produced, example :

```php

$recommender = new ExampleRecommendationService("http://localhost:7474");
$recommendation = $recommender->recommendMovieForUserWithId(460);

print_r($recommendations->getItems(1));

Array
(
    [0] => GraphAware\Reco4PHP\Result\Recommendation Object
        (
            [item:protected] => GraphAware\Bolt\Result\Type\Node Object
                (
                    [identity:protected] => 13248
                    [labels:protected] => Array
                        (
                            [0] => Movie
                        )

                    [properties:protected] => Array
                        (
                            [id] => 2571
                            [title] => Matrix, The (1999)
                        )

                )

            [scores:protected] => Array
                (
                    [rated_by_others] => GraphAware\Reco4PHP\Result\Score Object
                        (
                            [score:protected] => 1067
                            [scores:protected] => Array
                                (
                                    [0] => GraphAware\Reco4PHP\Result\SingleScore Object
                                        (
                                            [score:GraphAware\Reco4PHP\Result\SingleScore:private] => 1067
                                            [reason:GraphAware\Reco4PHP\Result\SingleScore:private] =>
                                        )

                                )

                        )

                    [reward_well_rated] => GraphAware\Reco4PHP\Result\Score Object
                        (
                            [score:protected] => 261
                            [scores:protected] => Array
                                (
                                    [0] => GraphAware\Reco4PHP\Result\SingleScore Object
                                        (
                                            [score:GraphAware\Reco4PHP\Result\SingleScore:private] => 261
                                            [reason:GraphAware\Reco4PHP\Result\SingleScore:private] =>
                                        )

                                )

                        )

                )

            [totalScore:protected] => 261
        )
)
```
### License

This library is released under the Apache v2 License, please read the attached `LICENSE` file.