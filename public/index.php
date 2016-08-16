<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$container = require 'config/container.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
	$_SERVER,
	$_GET,
	$_POST,
	$_COOKIE,
	$_FILES
);

$app = new App\Application($container);

$app->run($request);