<?php
namespace App\Controller;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use RuntimeException;

/**
 * The base controller for all controllers.
 */
abstract class AbstractController
{
	/**
	 * @var \\Interop\Container\ContainerInterface
	 */
	protected $container;

	/**
	 * Constructor
	 *
	 * @param \Interop\Container\ContainerInterface      $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Process a request from the Route
	 *
	 * @param  ServerRequestInterface $request Request
	 *
	 * @return \Zend\Diactoros\Response
	 */
	public function handleRequest(ServerRequestInterface $request)
	{
		$attributes = $request->getAttributes();

		if (isset($attributes['routeName']) === false)
		{
			throw new RuntimeException('routeName must be includes in the attributes.');
		}

		if (isset($attributes['action']) === false)
		{
			throw new InvalidArgumentException('Invalid action: ' . $attributes['routeName']);
		}

		$methodName =  $attributes['action'] . 'Action';

		$methodName = str_replace('-', '', $methodName);

		if (!method_exists($this, $methodName))
		{
			throw new InvalidArgumentException('Action with name ' . $methodName . ' not exists');
		}

		return [$this, $methodName]($request);
	}

	/**
	 * Return a JSON response
	 *
	 * @param  array $data
	 *
	 * @return \Zend\Diactoros\Response
	 */
	protected function toJson(array $data)
	{
		$response = new Response;

		$response->getBody()->write(json_encode($data));

		return $response->withHeader('Content-Type', 'application/json');
	}
}
