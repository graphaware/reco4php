# GraphAware Reco4PHP

## Neo4j based Recommendation Engine Framework for PHP

GraphAware Reco4PHP is a library for building complex recommendation engines atop Neo4j.

Features:

* Clean and flexible design
* Built-in algorithms and functions
* Ability to measure recommendation quality
* Built-in Cypher transaction management

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


### Installation

Require the dependency with `composer` :

```bash
composer require graphaware/reco4php
```

### Usage

#### Discovery

The first part of a Recommendation Engine is to find recommendations, this phase is generally called `Discovery`.

`Reco4PHP` comes with a built-in discovery mechanism and helps you to just concentrate on your business logic.

In this example, we will create a `Discovery Engine` that finds persons that have participated to the same meetup than you :

```php

use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class FindPeopleAttendedSameMeetup extends SingleDiscoveryEngine
{
    // You don't need to worry about finding the input, this part of the query is already done by the framework

    public function query()
    {
        $query = "MATCH (input)-[:ATTENDED]->(meetup)<-[:ATTENDED]-(reco)
        RETURN distinct reco, count(*) as score";

        return $query;
    }
}
```

### License

This library is released under the Apache v2 License, please read the attached `LICENSE` file.