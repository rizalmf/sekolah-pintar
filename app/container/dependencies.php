<?php

use app\src\Action\HomeAction;

$container = $app->getContainer();

// Handling jika request route tidak sesuai
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(403)
            ->withHeader('Content-Type', 'text/html')
            ->write('Forbidden');
    };
};
// Handling jika request method tidak sesuai
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $response->withStatus(403)
            // ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html')
            ->write('Forbidden');
    };
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));

    return $logger;
};

$container[HomeAction::class] = function ($c) {
    return new HomeAction();
};