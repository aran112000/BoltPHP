<?php
define('BACKGROUND_PROCESS', true);
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    // For environments where the DOCUMENT_ROOT isn't set, we generate it from the current file path
    $path_parts = explode(DIRECTORY_SEPARATOR, __FILE__);
    $path_parts = array_reverse($path_parts);
    foreach ($path_parts as $key => $path) {
        if ($path !== 'Bolt') {
            unset($path_parts[$key]);
        } else {
            unset($path_parts[$key]);
            break;
        }
    }

    $_SERVER['DOCUMENT_ROOT'] = implode(DIRECTORY_SEPARATOR, array_reverse($path_parts));
}

spl_autoload_register(function($class_name) {
    $class_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
	if (is_readable($class_path)) {
		require($class_path);
	}
});

if (defined('TRAVIS_CI')) {
    echo '<p>TRAVIS_CI is defined</p>' . "\n";
} else {
    echo '<p>TRAVIS_CI isn\'t defined</p>' . "\n";
}

$mysql = new \Bolt\Database\Mysql();
$mysql->doConnect('127.0.0.1', 'CloudFit', null, 'CloudFit');

$init = new \Bolt\Init();