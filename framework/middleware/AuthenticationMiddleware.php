<?php

namespace Framework\Middleware;

use Framework\Exceptions\DatabaseNotFound;
use Framework\ServiceContainer;
use Framework\UrlBuilder;
use Models\Repository;
use function Framework\getLoggedInUser;

class AuthenticationMiddleware extends MiddlewareAbstract
{
	public function handle()
	{

		$route = $_GET['route'] ?? '/';

		// Skip authentication for login route
		if ($route === '/login') {
			return $this->next && $this->next->handle();
		}

		// If the database is not set up yet, skip authentication checks
		try {
			$repository = ServiceContainer::get(Repository::class);
		} catch (DatabaseNotFound $e) {
			return $this->next && $this->next->handle();
		}

		if (!$repository->checkDatabaseExists()) {
			return $this->next && $this->next->handle();
		}

		$auth_enabled = $repository->userTableNotEmpty();

		// If auth is disabled, skip authentication check
		if (!$auth_enabled) {
			return $this->next && $this->next->handle();
		}

		$url_builder = ServiceContainer::get(UrlBuilder::class);
		$user = getLoggedInUser();

		// Redirect to login page if user is not authenticated
		if (!$user) {
			header('Location: ' . $url_builder->build('/login'));
			exit;
		}

		return $this->next && $this->next->handle();
	}
}