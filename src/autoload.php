<?php
// 
// autoload.php
// Autoloads classses required by project.

spl_autoload_register('autoloader');
require_once __DIR__ . '/../vendor/autoload.php';

function autoloader($class)
{
	$path = __DIR__ . "/{$class}.php";
	if (file_exists($path)) {
		// echo PHP_EOL, "{$class} required", PHP_EOL;
		require $path;
	}
}
