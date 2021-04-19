<?php

use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Dotenv\Dotenv;

if(! defined('ENVIRONMENT') )
{
    if (isset($_SERVER['SERVER_NAME'])) {
        $domain = $_SERVER['SERVER_NAME'];
    } else {
        $domain = 'cli';
    }

    //============================
    //NOTE: Global environment
    //============================
    switch($domain) {
        case 'api.antrian.com': // future
            define('ENVIRONMENT', 'production');
            break;
        case 'xxx.xxx.xxx.xxx' :
            define('ENVIRONMENT', 'testing');
            break;

        case 'localhost' :
            define('ENVIRONMENT', 'development');
            break;

        case 'cli':
        default :
            define('ENVIRONMENT', 'development');
            break;
    }
}
$file = '.env.' . strtolower(ENVIRONMENT);

$factory = new DotenvFactory([new EnvConstAdapter(), new ServerConstAdapter()]);
Dotenv::create(__DIR__.'/../', $file, $factory)->load();
