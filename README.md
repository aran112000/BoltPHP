BoltPHP
================

[![Build Status](https://travis-ci.org/aran112000/BoltPHP.svg?branch=dev)](https://travis-ci.org/aran112000/BoltPHP)

[![Codacy Badge](https://www.codacy.com/project/badge/ce03c9f6e91b4c3a86579ae39fccfacd)](https://www.codacy.com/public/cdtreeks/BoltPHP)

A simple PHP framework for rapid application development

## Want to run a MySQL query and iterate through it's results? ##
Well that couldn't be simpler, the following code will create, execute and loop through the following MySQL query outputting both columns fetched to the screen:

```
<?php
$query = new \Bolt\Database\Mysql();
foreach ($query->select(['column_1', 'column_2'])->from('MyTable')->where(['active' => 1'])->limit(50) as $my_table_results) {
    // Results are always returned as an object by default
    echo $my_table_results->column_1 . ', ' . $my_table_results->column_2 . '\n';

    // Should an object exist matching your queries FROM table name, then it will be auto populated with your result set
    // This allows you to create your own functions to act on the result set making your code more reusable, take this
    // hypothetical example, we could create a function that returns the URL that we use to access this record's web page
    echo $my_table_results->getUrl();
}
```

## Generated MySQL query ##
```
SELECT `YourTableName`.`column_1`,
       `YourTableName`.`column_2`
FROM `YourTableName`
WHERE `YourTableName`.`active` = 1
LIMIT 50;
```
