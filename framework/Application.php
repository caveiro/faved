<?php

namespace Framework;


use Exception;
use Framework\Exceptions\DataWriteException;
use Framework\Exceptions\ForbiddenException;
use Framework\Exceptions\NotFoundException;
use Framework\Exceptions\ValidationException;
use Framework\Exceptions\DatabaseNotFound;

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
			$output = $controller();
		} catch (DatabaseNotFound $e) {
			$url_builder = ServiceContainer::get(UrlBuilder::class);
			header("Location: " . $url_builder->build('/setup'));
			return;
		} catch (ValidationException | DataWriteException $e) {
			FlashMessages::set('error', $e->getMessage());
			$url_builder = ServiceContainer::get(UrlBuilder::class);
			$referrer = $_SERVER['HTTP_REFERER'] ?? $url_builder->build('/');
			header("Location: " . $referrer);
			return;
		} catch (ForbiddenException $e) {
			http_response_code(403);
			$output = renderPage('error', 'primary', ['message' => "403 - {$e->getMessage()}"]);
		} catch (NotFoundException $e) {
			http_response_code(404);
			$output = renderPage('error', 'primary', ['message' => "404 - {$e->getMessage()}"]);
		} catch (Exception $e) {
			http_response_code(500);
			$output = renderPage('error', 'primary', ['message' => "500 - {$e->getMessage()}"]);
		}

		echo $output;
	}
}

