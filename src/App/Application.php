<?php
namespace App;

use Exception;
use Aura\Router\RouterContainer;
use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use ReflectionClass;

class Application
{
	private $container;

	private $routes;

	private $routerMatcher;

	private $routerMap;

	/**
	 * Application construct
	 *
	 * @param Interop\Container\ContainerInterface $container Container of services
	 */
	function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->routes = [];
		$this->createRouter();
		$this->injectRoutes($this->container['routes']);
	}

	/**
	 * Run the application
	 *
	 * @param  Psr\Http\Message\ServerRequestInterface $request $request
	 */
	public function run(ServerRequestInterface $request)
	{
		try
		{
			$route = $this->routerMatcher->match($request);

			if ($route === false)
			{
				throw new InvalidArgumentException('Invalid path');
			}

			foreach ($route->attributes as $key => $value)
			{
				$request = $request->withAttribute($key, $value);
			}

			$request = $request->
				withAttribute('controller', $route->handler)->
				withAttribute('routeName', $route->name);

			$reflector = new ReflectionClass($route->handler);

			$controller = $reflector->newInstance($this->container);

			$response = $controller->handleRequest($request);
		}
		catch (Exception $e)
		{
			$response = new Response;
			$response->getBody()->write($e->getMessage());
		}

		$this->emit($response);
	}

	/**
	 * Init Router stuff
	 */
	private function createRouter()
	{
		$routerContainer = new RouterContainer();
		$this->routerMap = $routerContainer->getMap();
		$this->routerMatcher = $routerContainer->getMatcher();
	}

	/**
	 * Emit the response (Set up the header according to the response object)
	 *
	 * @param Psr\Http\Message\ResponseInterface $response
	 */
	private function emit(ResponseInterface $response)
	{
		http_response_code($response->getStatusCode());

		foreach ($response->getHeaders() as $key => $values)
		{
			foreach ($values as $i => $value)
			{
				$name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $key)));
				header("$name: $value", $i === 0);
			}
		}

		echo $response->getBody();
	}

	/**
	 * Set up routes from the config file
	 *
	 * @param  array  $routes Route list
	 */
	private function injectRoutes (array $routes)
	{
		foreach ($routes as $routeName => $params)
		{
			if (isset($params['path']) === false || is_string($params['path']) === false)
			{
				throw new RuntimeException('The route with name "' . $routeName . '" is invalid.');
			}

			$methods = isset($params['allowed_methods']) === false ? ['GET'] : $params['allowed_methods'];

			if (is_array($methods) === false)
			{
				throw new RuntimeException('The route with name "' . $routeName . '" has invalid allowed methods.');
			}

			$defaults = isset($params['defaults']) === false ? [] : $params['defaults'];

			$route = [
				'controller' => $params['defaults']['controller'],
				'action' => $params['defaults']['action'],
			];

			$this->routerMap->route($routeName, $params['path'], $route['controller'])->
				allows($methods)->
				defaults($defaults);

			$this->routes[$routeName] = $route;
		}
	}
}