# GraphAware Reco4PHP

## Neo4j based Recommendation Engine Framework for PHP

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

### License

This library is released under the Apache v2 License, please read the attached `LICENSE` file.