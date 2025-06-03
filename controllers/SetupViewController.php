<?php

namespace Controllers;

use Config;
use Framework\ControllerInterface;
use Framework\CSRFProtection;
use Framework\Exceptions\DatabaseNotFound;
use Framework\FlashMessages;
use Framework\ServiceContainer;
use Framework\UrlBuilder;
use Models\Repository;
use function Framework\renderPage;

class SetupViewController implements ControllerInterface
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

		return renderPage('setup', 'primary', [
			'db_file' => str_replace(ROOT_DIR, '', Config::DB_PATH),
			'url_builder' => $url_builder,
			'csrf_token' => CSRFProtection::generateToken(),
			'flash' => FlashMessages::pull(),
		]);
	}
}
