<?php
use Xtreamwayz\Pimple\Container;

$services = Zend\Config\Factory::fromFiles([
	__DIR__ . '/global/services.php',
	__DIR__ . '/local/services.php',
], false);

$container = new Container();

foreach ($services as $key => $value)
{
	$container[$key] = $value;
}

return $container;
