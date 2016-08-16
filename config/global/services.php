<?php

return [
	'config' => function ()
	{
		return Zend\Config\Factory::fromFiles([
			__DIR__ . '/../../config/global/settings.php',
			__DIR__ . '/../../config/local/settings.php',
		], true);
	},

	'routes' => function ()
	{
		return Zend\Config\Factory::fromFile(__DIR__ . '/../../config/global/routes.php', false);
	}
];