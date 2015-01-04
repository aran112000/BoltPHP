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
	if (is_readable($class_name . '.php')) {
		require($class_name . '.php');
	} else {
        echo '<p>File not found... ' . $class_name . '.php</p>'."\n";
    }
});

$init = new \Bolt\Init();