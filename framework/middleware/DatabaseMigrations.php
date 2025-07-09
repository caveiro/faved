<?php

namespace Framework\Middleware;

use Framework\ServiceContainer;
use Models\Repository;

class DatabaseMigrations extends MiddlewareAbstract
{
	public function handle()
	{
		$repository = ServiceContainer::get(Repository::class);

		$repository->migrate();

		return $this->next && $this->next->handle();
	}
}