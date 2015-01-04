<?php
define('BACKGROUND_PROCESS', true);
$_SERVER['DOCUMENT_ROOT'] = 'E:\Bolt';

spl_autoload_register(function($class_name) {
	if (is_readable($class_name . '.php')) {
		require($class_name . '.php');
	}
});

$init = new \Bolt\Init();