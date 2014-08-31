<?php
error_reporting(E_ALL);
spl_autoload_register(function($class_name) {
	require($class_name . '.php');
});

$init = new \Core\Init();