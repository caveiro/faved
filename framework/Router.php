<?php

namespace Framework;

use Framework\Exceptions\NotFoundException;

class Router
{
	protected $routes;

	public function __construct(array $routes)
	{
		$this->routes = $routes;

	}

	public function match_controller(string $url, string $method): string
	{
		$routes = flattenArray($this->routes);
		$route_id = $url . $method;
		if (!isset($routes[$route_id])) {
			throw new NotFoundException("Route $method $url not found");
		}

		return $routes[$route_id];
	}
}


