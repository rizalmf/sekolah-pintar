<?php

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => 1,

        // database settings
        'pdo' => [
            'dsn' => 'mysql:host='.$_ENV['DB_HOST'].':'.$_ENV['DB_PORT'].';dbname='.$_ENV['DB_NAME'].';charset=utf8',
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
        ],

        // eloquent config
        'eloquentcfg' => [
            'driver'    => 'mysql',
            'host'      => $_ENV['DB_HOST'],
            'port'      => $_ENV['DB_PORT'],
            'database'  => $_ENV['DB_NAME'],
            'username'  => $_ENV['DB_USER'],
            'password'  => $_ENV['DB_PASS'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ],

        // monolog settings
        'logger' => [
            'stream' => $_ENV['LOG_QUERY'],
            'name' => 'app',
            'path' => __DIR__.'/../log/'.date('Y-m-d').'.log',
        ]
        
    ],
];
