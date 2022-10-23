# GraphAware Reco4PHP

## Neo4j based Recommendation Engine Framework for PHP

GraphAware Reco4PHP is a library for building complex recommendation engines atop Neo4j.

[![Build Status](https://github.com/graphaware/reco4php/workflows/main/badge.svg)](https://github.com/graphaware/reco4php/actions)

Features:

* Clean and flexible design
* Built-in algorithms and functions
* Ability to measure recommendation quality
* Built-in Cypher transaction management

Requirements:

* PHP8.0+
* Neo4j 3.5 / 4.0+

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

The dataset is publicly available here : http://grouplens.org/datasets/movielens/. The data set to download is in the **MovieLens Latest Datasets** section and is named `ml-latest-small.zip`.

Once downloaded and extracted the archive, you can run the following Cypher statements for importing the dataset, just adapt the file urls to match your actual path to the files :

> **_NOTE:_**  This is Cypher version 4.4 syntax.

```
CREATE CONSTRAINT FOR (m:Movie) REQUIRE m.id IS UNIQUE;
CREATE CONSTRAINT FOR (g:Genre) REQUIRE g.name IS UNIQUE;
CREATE CONSTRAINT FOR (u:User) REQUIRE u.id IS UNIQUE;
```

```
LOAD CSV WITH HEADERS FROM "file:///Users/ikwattro/dev/movielens/movies.csv" AS row
WITH row
MERGE (movie:Movie {id: toInteger(row.movieId)})
ON CREATE SET movie.title = row.title
WITH movie, row
UNWIND split(row.genres, '|') as genre
MERGE (g:Genre {name: genre})
MERGE (movie)-[:HAS_GENRE]->(g)
```


```
:auto LOAD CSV WITH HEADERS FROM "file:///Users/ikwattro/dev/movielens/ratings.csv" AS row
WITH row
LIMIT 500
CALL {
    WITH row
    MATCH (movie:Movie {id: toInteger(row.movieId)})
    MERGE (user:User {id: toInteger(row.userId)})
    MERGE (user)-[r:RATED]->(movie)
    ON CREATE SET r.rating = toInteger(row.rating), r.timestamp = toInteger(row.timestamp)
} IN TRANSACTIONS
```

For the purpose of the example, we will assume we are recommending movies for the User with ID 4.


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
the methods of the upper interfaces, here are how you would create your first discovery engines :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class RatedByOthers extends SingleDiscoveryEngine
{
    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (input:User) WHERE id(input) = $id
        MATCH (input)-[:RATED]->(m)<-[:RATED]-(o)
        WITH distinct o
        MATCH (o)-[:RATED]->(reco)
        RETURN distinct reco LIMIT 500';

        return Statement::create($query, ['id' => $input->getId()]);
    }

    public function name(): string
    {
        return 'rated_by_others';
    }
}
```

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\Discovery;

use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class FromSameGenreILike extends SingleDiscoveryEngine
{
    public function name(): string
    {
        return 'from_genre_i_like';
    }

    public function discoveryQuery(Node $input, Context $context): Statement
    {
        $query = 'MATCH (input) WHERE id(input) = $id
        MATCH (input)-[r:RATED]->(movie)-[:HAS_GENRE]->(genre)
        WITH distinct genre, sum(r.rating) as score
        ORDER BY score DESC
        LIMIT 15
        MATCH (genre)<-[:HAS_GENRE]-(reco)
        RETURN reco
        LIMIT 200';

        return Statement::create($query, ['id' => $input->getId()]);
    }
}
```

The `discoveryQuery` method should return a `Statement` object containing the query for finding recommendations,
the `name` method should return a string describing the name of your engine (this is mostly for logging purposes).

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

use GraphAware\Reco4PHP\Filter\Filter;
use Laudis\Neo4j\Types\Node;

class ExcludeOldMovies implements Filter
{
    public function doInclude(Node $input, Node $item): bool
    {
        $title = (string) $item->getProperty('title');
        preg_match('/(?:\()\d+(?:\))/', $title, $matches);

        if (isset($matches[0])) {
            $y = str_replace('(', '', $matches[0]);
            $y = str_replace(')', '', $y);
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

use GraphAware\Reco4PHP\Filter\BaseBlacklistBuilder;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\Node;

class AlreadyRatedBlackList extends BaseBlacklistBuilder
{
    public function blacklistQuery(Node $input): Statement
    {
        $query = 'MATCH (input) WHERE id(input) = $inputId
        MATCH (input)-[:RATED]->(movie)
        RETURN movie as item';

        return Statement::create($query, ['inputId' => $input->getId()]);
    }

    public function name(): string
    {
        return 'already_rated';
    }
}
```

You really just need to add the logic for matching the nodes that should be blacklisted, the framework takes care for filtering the recommended
nodes against the blacklists provided.

#### Post Processors

`Post Processors` are meant to add additional scoring to the recommended items. In our example, we could reward a produced recommendation if it has more than 10 ratings :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example\PostProcessing;

use GraphAware\Reco4PHP\Post\RecommendationSetPostProcessor;
use GraphAware\Reco4PHP\Result\Recommendation;
use GraphAware\Reco4PHP\Result\Recommendations;
use GraphAware\Reco4PHP\Result\SingleScore;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;
use Laudis\Neo4j\Types\Node;

class RewardWellRated extends RecommendationSetPostProcessor
{
    public function buildQuery(Node $input, Recommendations $recommendations): Statement
    {
        $query = 'UNWIND $ids as id
        MATCH (n) WHERE id(n) = id
        MATCH (n)<-[r:RATED]-(u)
        RETURN id(n) as id, sum(r.rating) as score';

        $ids = [];
        foreach ($recommendations->getItems() as $item) {
            $ids[] = $item->item()->getId();
        }

        return Statement::create($query, ['ids' => $ids]);
    }

    public function postProcess(Node $input, Recommendation $recommendation, CypherMap $result): void
    {
        $recommendation->addScore($this->name(), new SingleScore((float) $result->get('score'), 'total_ratings_relationships'));
    }

    public function name(): string
    {
        return 'reward_well_rated';
    }
}
```

#### Wiring all together

Now that our components are created, we need to build effectively our recommendation engine :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Engine\BaseRecommendationEngine;
use GraphAware\Reco4PHP\Tests\Example\Discovery\FromSameGenreILike;
use GraphAware\Reco4PHP\Tests\Example\Discovery\RatedByOthers;
use GraphAware\Reco4PHP\Tests\Example\Filter\AlreadyRatedBlackList;
use GraphAware\Reco4PHP\Tests\Example\Filter\ExcludeOldMovies;
use GraphAware\Reco4PHP\Tests\Example\PostProcessing\RewardWellRated;

class ExampleRecommendationEngine extends BaseRecommendationEngine
{
    public function name(): string
    {
        return 'user_movie_reco';
    }

    public function discoveryEngines(): array
    {
        return [
            new RatedByOthers(),
            new FromSameGenreILike(),
        ];
    }

    public function blacklistBuilders(): array
    {
        return [
            new AlreadyRatedBlackList(),
        ];
    }

    public function postProcessors(): array
    {
        return [
            new RewardWellRated(),
        ];
    }

    public function filters(): array
    {
        return [
            new ExcludeOldMovies(),
        ];
    }
}
```

As in your recommender service, you might have multiple recommendation engines serving different recommendations, the last step is to create this service and register each `RecommendationEngine` you have created.
You'll need to provide also a connection to your Neo4j database, in your application this could look like this :

```php
<?php

namespace GraphAware\Reco4PHP\Tests\Example;

use GraphAware\Reco4PHP\Context\SimpleContext;
use GraphAware\Reco4PHP\RecommenderService;
use GraphAware\Reco4PHP\Result\Recommendations;

class ExampleRecommenderService
{
    protected RecommenderService $service;

    /**
     * ExampleRecommenderService constructor.
     */
    public function __construct(string $databaseUri)
    {
        $this->service = RecommenderService::create($databaseUri);
        $this->service->registerRecommendationEngine(new ExampleRecommendationEngine());
    }

    public function recommendMovieForUserWithId(int $id): Recommendations
    {
        $input = $this->service->findInputBy('User', 'id', $id);
        $recommendationEngine = $this->service->getRecommender('user_movie_reco');

        return $recommendationEngine->recommend($input, new SimpleContext());
    }
}
```

#### Inspecting recommendations

The `recommend()` method on a recommendation engine will returns you a `Recommendations` object which contains a set of `Recommendation` that holds the recommended item and their score.

Each score is inserted so you can easily inspect why such recommendation has been produced, example :

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use GraphAware\Reco4PHP\Tests\Example\ExampleRecommenderService;

$recommender = new ExampleRecommenderService('bolt://localhost:7687');
$recommendations = $recommender->recommendMovieForUserWithId(4);

print_r($recommendations->getItems(1));

Array
(
    [0] => GraphAware\Reco4PHP\Result\Recommendation Object
        (
            [item:protected] => Laudis\Neo4j\Types\Node Object
                (
                    [id:Laudis\Neo4j\Types\Node:private] => 2700
                    [labels:Laudis\Neo4j\Types\Node:private] => Laudis\Neo4j\Types\CypherList Object
                        (
                            [keyCache:protected] => Array
                                (
                                    [0] => 0
                                )

                            [cache:protected] => Array
                                (
                                    [0] => Movie
                                )

                            [cacheLimit:Laudis\Neo4j\Types\AbstractCypherSequence:private] => 9223372036854775807
                            [currentPosition:protected] => 0
                            [generatorPosition:protected] => 1
                            [generator:protected] => ArrayIterator Object
                                (
                                    [storage:ArrayIterator:private] => Array
                                        (
                                        )

                                )

                        )

                    [properties:Laudis\Neo4j\Types\Node:private] => Laudis\Neo4j\Types\CypherMap Object
                        (
                            [keyCache:protected] => Array
                                (
                                    [0] => id
                                    [1] => title
                                )

                            [cache:protected] => Array
                                (
                                    [id] => 3578
                                    [title] => Gladiator (2000)
                                )

                            [cacheLimit:Laudis\Neo4j\Types\AbstractCypherSequence:private] => 9223372036854775807
                            [currentPosition:protected] => 0
                            [generatorPosition:protected] => 2
                            [generator:protected] => ArrayIterator Object
                                (
                                    [storage:ArrayIterator:private] => Array
                                        (
                                        )

                                )

                        )

                )

            [scores:protected] => Array
                (
                    [rated_by_others] => GraphAware\Reco4PHP\Result\Score Object
                        (
                            [score:protected] => 1
                            [scores:protected] => Array
                                (
                                    [0] => GraphAware\Reco4PHP\Result\SingleScore Object
                                        (
                                            [score:GraphAware\Reco4PHP\Result\SingleScore:private] => 1
                                        )

                                )

                        )

                    [reward_well_rated] => GraphAware\Reco4PHP\Result\Score Object
                        (
                            [score:protected] => 9
                            [scores:protected] => Array
                                (
                                    [0] => GraphAware\Reco4PHP\Result\SingleScore Object
                                        (
                                            [score:GraphAware\Reco4PHP\Result\SingleScore:private] => 9
                                            [reason:GraphAware\Reco4PHP\Result\SingleScore:private] => total_ratings_relationships
                                        )

                                )

                        )

                )

            [totalScore:protected] => 10
        )

)
```
### License

This library is released under the Apache v2 License, please read the attached `LICENSE` file.

Commercial support or custom development/extension available upon request to info@graphaware.com.
