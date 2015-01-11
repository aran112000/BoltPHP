<?php
spl_autoload_register(function ($class_name) {
    $class_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    if (is_readable($class_path)) {
        require($class_path);
    }
});

// Composer autoloader
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Bolt' . DIRECTORY_SEPARATOR . 'Libs' . DIRECTORY_SEPARATOR . 'autoload.php');

$init = new \Bolt\Init();