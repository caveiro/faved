<?php

namespace Controllers;

use Config;
use Framework\Exceptions\DatabaseNotFound;
use Framework\FlashMessages;
use Framework\ServiceContainer;
use Framework\UrlBuilder;
use Models\Repository;
use PDO;

class SetupRunController
{
	public function __invoke()
	{
		try {
			// Check if database already exists
			$repository = ServiceContainer::get(Repository::class);
			$db_exists = $repository->checkDatabaseExists();

		} catch (DatabaseNotFound $e) {
			$db_exists = false;
		}

		$url_builder = ServiceContainer::get(UrlBuilder::class);

		if ($db_exists) {
			FlashMessages::set('info', 'Database already exists');
			header("Location: " . $url_builder->build('/'));
			return;
		}

		$db_path = Config::getDBPath();
		$pdo = new PDO("sqlite:{$db_path}");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$repository = new Repository($pdo);

		// Create database tables
		$result = $repository->setupDatabase();

		if (!$result) {
			FlashMessages::set('error', 'Failed to set up database');
			header("Location: " . $url_builder->build('/setup'));
			return;
		}


		FlashMessages::set('success', 'Database setup completed successfully');
		header("Location: " . $url_builder->build('/'));
	}
}