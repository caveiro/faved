<?php

use Controllers\ItemCreateUpdateController;
use Controllers\ItemDeleteController;
use Controllers\ItemEditController;
use Controllers\ItemsController;
use Controllers\SetupViewController;
use Controllers\SetupRunController;
use Controllers\TagDeleteController;
use Controllers\TagEditController;
use Controllers\TagUpdateController;

return [
	'/' => [
		'GET' => ItemsController::class
	],
	'/setup' => [
		'GET' => SetupViewController::class,
		'POST' => SetupRunController::class
	],
	'/tag' => [
		'GET' => TagEditController::class,
		'POST' => TagUpdateController::class,
		'DELETE' => TagDeleteController::class,
	],
	'/item' => [
		'GET' => ItemEditController::class,
		'POST' => ItemCreateUpdateController::class,
		'DELETE' => ItemDeleteController::class,
	],
];

