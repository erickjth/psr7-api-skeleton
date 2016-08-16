<?php

use App\Controller;

return [
	'home' => [
		'path' => '/',
		'defaults' => [
			'controller' => Controller\DefaultController::class,
			'action' => 'home'
		],
		'allowed_methods' => ['GET'/*, 'POST', 'DELETE', 'PATCH'*/]
	],

	'account_list' => [
		'path' => '/account',
		'defaults' => [
			'controller' => Controller\AccountController::class,
			'action' => 'list'
		],
		'allowed_methods' => ['GET'/*, 'POST', 'DELETE', 'PATCH'*/]
	],

	'account_resource' => [
		'path' => '/account/{id}',
		'defaults' => [
			'controller' => Controller\AccountController::class,
			'action' => 'get'
		],
		'allowed_methods' => ['GET'/*, 'POST', 'DELETE', 'PATCH'*/]
	],
];