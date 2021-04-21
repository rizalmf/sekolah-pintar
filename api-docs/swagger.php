<?php

require __DIR__.'/../vendor/autoload.php';

// Read Env
require __DIR__.'/../config/dotenv.php';

if ($_SERVER['SHOW_DOCS'] == 1) {

    $openapi = OpenApi\scan(__DIR__.'/../app/src/Action');
    header('Content-Type: application/json');
    echo $openapi->toJSON();
}

// access docs: {host}/api-docs