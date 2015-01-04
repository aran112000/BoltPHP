<?php
define('TRAVIS_CI', true);

$mysql = new \Bolt\Database\Mysql();
$mysql->doConnect('127.0.0.1', 'root', null, 'CloudFit');