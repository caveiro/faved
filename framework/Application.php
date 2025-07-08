<?php

namespace Framework;


use Exception;
use Framework\Exceptions\DatabaseNotFound;
use Framework\Exceptions\DataWriteException;
use Framework\Exceptions\ForbiddenException;
use Framework\Exceptions\NotFoundException;
use Framework\Exceptions\ValidationException;

class Application
{
	public function __construct(protected array $routes, protected array $middleware_classes)
	{
	}

	public function run($route, $method)
	{
		try {
			foreach (array_reverse($this->middleware_classes) as $middleware_class) {
				$middleware = new $middleware_class($middleware ?? null);
			}
			isset($middleware) && $middleware->handle();

			$router = new Router($this->routes);
			$controller_class = $router->match_controller($route, $method);

			$controller = new $controller_class();
			$response = $controller();
		} catch (DatabaseNotFound $e) {
			$url_builder = ServiceContainer::get(UrlBuilder::class);
			$response = redirect($url_builder->build('/setup'));
		} catch (ValidationException|DataWriteException $e) {
			FlashMessages::set('error', $e->getMessage());
			$url_builder = ServiceContainer::get(UrlBuilder::class);
			$referrer = $_SERVER['HTTP_REFERER'] ?? $url_builder->build('/');
			$response = redirect($referrer);
		} catch (ForbiddenException $e) {
			http_response_code(403);
			$response = page('error', ['message' => "403 - {$e->getMessage()}"])
				->layout('primary');
		} catch (NotFoundException $e) {
			http_response_code(404);
			$response = page('error', ['message' => "404 - {$e->getMessage()}"])
				->layout('primary');
		} catch (Exception $e) {
			http_response_code(500);
			$response = page('error', ['message' => "500 - {$e->getMessage()}"])
				->layout('primary');
		}

		$response->yield();
	}
}

