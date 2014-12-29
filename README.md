BoltPHP
================

A simple PHP framework for rapid application development

# Want to run a MySQL query and iterate through it's results? #
Well that couldn't be simpler, the following code will create, execute and loop through the following MySQL query outputting both columns fetched to the screen:

```
<?php
$query = new \Bolt\Database\Mysql();
foreach ($query->select(['column_1', 'column_2'])->from('YourTableName')->where(['active' => 1'])->limit(50) as $results) {
    echo $results->column_1 . ', ' . $results->column_2 . '\n';
}
```

## Generated MySQL query ##
```
SELECT `YourTableName`.`column_1`, `YourTableName`.`column_2` FROM `YourTableName` WHERE `YourTableName`.`active` = 1 LIMIT 50
```