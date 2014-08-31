<?php
spl_autoload_register(function($class_name) {
	if (is_readable($class_name . '.php')) {
		require($class_name . '.php');
	}
});