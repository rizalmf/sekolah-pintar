<?php

use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Dotenv\Dotenv;

/*
| -------------------------------------------------------------------
|   IP staging & staging-beta
| -------------------------------------------------------------------
|
*/
$DEVELOPMENT_DOMAIN = [
    'localhost',
    '127.0.0.1',
];

/*
| -------------------------------------------------------------------
|   IP production
| -------------------------------------------------------------------
|
*/
$PRODUCTION_DOMAIN = [
    'sekolah.rizalmaulanaf.masuk.web.id'
];

/*
| -------------------------------------------------------------------
|   PORTS public 
| -------------------------------------------------------------------
| - staging[DEV], 
| - staging-beta[BETA],
| - production[PROD] 
|
*/
$PORTS = [
    'DEV' => 80,
    // 'PROD' => 80
];

/*
| -------------------------------------------------------------------
|   Define domain & port
| -------------------------------------------------------------------
|
*/
$domain = $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'];

/*
| -------------------------------------------------------------------
|   Define Environment
| -------------------------------------------------------------------
|
*/
if (in_array($domain, $PRODUCTION_DOMAIN)) {
    define('ENVIRONMENT', 'production');
} else 
    define('ENVIRONMENT', 'development');

/*
| -------------------------------------------------------------------
|   Load Environment
| -------------------------------------------------------------------
|
*/
$file = '.env.' . strtolower(ENVIRONMENT);

$factory = new DotenvFactory([new EnvConstAdapter(), new ServerConstAdapter()]);
Dotenv::create(__DIR__.'/../', $file, $factory)->load();
