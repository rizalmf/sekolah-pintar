<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require __DIR__.'/../vendor/autoload.php';

// Register dotenv
require __DIR__.'/../config/dotenv.php';

// Instantiate the app
$settings = require __DIR__.'/../config/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__.'/container/dependencies.php';

// Register Eloquent ORM
require __DIR__.'/../config/eloquent/eloquentcfg.php';

// Register middleware
require __DIR__.'/../middlewares/middleware.php';

// Register routes
require __DIR__.'/../routes/routes.php';

// Run!
$app->run();
