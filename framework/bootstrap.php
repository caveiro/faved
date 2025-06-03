<?php
// Load framework files
require_once ROOT_DIR . '/framework/helper-functions.php';
require_once ROOT_DIR . '/framework/Router.php';
require_once ROOT_DIR . '/framework/ControllerInterface.php';
require_once ROOT_DIR . '/framework/Application.php';
require_once ROOT_DIR . '/framework/ServiceContainer.php';
require_once ROOT_DIR . '/framework/UrlBuilder.php';
require_once ROOT_DIR . '/framework/FlashMessages.php';
require_once ROOT_DIR . '/framework/CSRFProtection.php';
require_once ROOT_DIR . '/framework/exceptions/NotFoundException.php';
require_once ROOT_DIR . '/framework/exceptions/ValidationException.php';
require_once ROOT_DIR . '/framework/exceptions/DataWriteException.php';
require_once ROOT_DIR . '/framework/exceptions/DatabaseNotFound.php';
require_once ROOT_DIR . '/framework/exceptions/ForbiddenException.php';
require_once ROOT_DIR . '/framework/middleware/MiddlewareAbstract.php';
require_once ROOT_DIR . '/framework/middleware/CSRFMiddleware.php';

// Load configuration
require_once ROOT_DIR . '/config.php';

// Load routes
$routes = require ROOT_DIR . '/routes.php';

// Load routes controllers
foreach (glob(ROOT_DIR . "/controllers/*.php") as $controller_file) {
	require_once $controller_file;
}

use Framework\Application;
use Framework\ServiceContainer;
use Framework\UrlBuilder;
use Framework\Middleware\CSRFMiddleware;

session_start();

date_default_timezone_set('UTC');

// Bind services
ServiceContainer::bind(UrlBuilder::class, function () {
	$url_builder = new UrlBuilder(
		'/index.php'
	);
	return $url_builder;
});

$middleware_classes = [
	CSRFMiddleware::class,
];

// Load project-specific files and services
require_once ROOT_DIR . '/init.php';

$route = $_GET["route"] ?? '/';
$method = $_POST['force-method'] ?? $_SERVER["REQUEST_METHOD"];
$app = new Application($routes, $middleware_classes);
$app->run($route, $method);