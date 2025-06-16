<?php

namespace Controllers;

use Config;
use Framework\Exceptions\DatabaseNotFound;
use Framework\FlashMessages;
use Framework\ServiceContainer;
use Framework\UrlBuilder;
use Models\Repository;
use Models\TagCreator;
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

		/*
		 * Add demo content
		 */
		$tag_creator = ServiceContainer::get(TagCreator::class);
		$demo_content_tag_id = $tag_creator->createTag('Demo links', 'These are links for demo purposes', 0, 'aqua', true);
		$software_category_tag_id = $tag_creator->createTag('Software category', 'Software categories are nested within this tag', 0, 'red', false);
		$bookmark_manager_category_tag_id = $tag_creator->createTag('Bookmark managers', '', $software_category_tag_id, 'gray', false);
		$github_repos_tag_id = $tag_creator->createTag('GitHub repositories', '', 0, 'gray', false);

		$item_id = $repository->createItem(
			'Faved Demo',
			'Try out Faved online before installing it on your machine. Demo sites are provided for testing and are deleted after one month.',
			'https://demo.faved.dev/',
			'',
			'',
			null
		);
		$repository->attachItemTags([$demo_content_tag_id]
			, $item_id);

		$item_id = $repository->createItem(
			'GitHub - denho/faved: Free open-source bookmark manager with customisable nested tags. Super fast and lightweight. All data is stored locally.',
			'Free open-source bookmark manager with customisable nested tags. Super fast and lightweight. All data is stored locally. - denho/faved',
			'https://github.com/denho/faved',
			'',
			'',
			null
		);
		$repository->attachItemTags([$demo_content_tag_id, $github_repos_tag_id]
			, $item_id);

		$item_id = $repository->createItem(
			' Faved - Organize Your Bookmarks ',
			'A self-hosted, open-source solution to store, categorize, and access your bookmarks from anywhere.',
			'https://faved.dev/',
			'Faved main site',
			'',
			null
		);
		$repository->attachItemTags([$demo_content_tag_id, $bookmark_manager_category_tag_id], $item_id);

		FlashMessages::set('success', 'Database setup completed successfully');
		header("Location: " . $url_builder->build('/'));
	}
}