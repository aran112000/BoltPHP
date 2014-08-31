<?php
spl_autoload_register(function($class_name) {
	require('C:\\Winginx\\home\\allure\\Allure-Cosmetics\\' . $class_name . '.php');
});