# Upgrades

## 2.0 to 3.0

```diff
- use GraphAware\Common\Type\Node;
- use GraphAware\Common\Type\NodeInterface;
+ use Laudis\Neo4j\Types\Node;

- $node->identity()
+ $node->getId()

- $node->get('login')
+ $node->getProperty('login')

- $node->hasValue($key)
+ $node->getProperties()->hasKey($key)

- $node->value($key)
+ $node->getProperty($key)
```

```diff
- use GraphAware\Common\Cypher\StatementInterface;
- use GraphAware\Common\Cypher\Statement;
+ use Laudis\Neo4j\Databags\Statement;
```

```diff
- use GraphAware\Common\Result\Record;
- use GraphAware\Common\Result\RecordViewInterface;
+ use Laudis\Neo4j\Types\CypherMap;

- RecordViewInterface $record
- Record $record
+ CypherMap $result

- $record->hasValue($key)
+ $result->hasKey($key)

- $record->value($key)
+ $result->get($key)
```


```diff
- use GraphAware\Common\Result\ResultCollection;
+ use GraphAware\Reco4PHP\Result\ResultCollection;
```

```diff
- use GraphAware\Common\Result\Result;
+ use Laudis\Neo4j\Types\CypherList;
+ use Laudis\Neo4j\Types\CypherMap;

- Result $result
+ CypherList $results

- foreach ($result->records() as $record) {
+ /** @var CypherMap $result */
+ foreach ($results as $result) {

- $result->getRecord()
+ $results->first()

- $result->firstRecord()
+ $results->first()
```

```diff
- use GraphAware\Common\Result\RecordCursorInterface;
+ use Laudis\Neo4j\Types\CypherList;

- RecordCursorInterface $result
+ CypherList $results
```
