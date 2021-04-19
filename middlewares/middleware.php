<?php

// Register global/single route middlewares here

// register paths
require __DIR__.'/../middlewares/core/Core.php';
require __DIR__.'/../middlewares/Filter.php';
require __DIR__.'/../middlewares/api/Auth.php';
require __DIR__.'/../middlewares/api/admin/Auth.php';

use app\middleware\Filter;

// global middleware untuk filter xss
$app->add(new Filter);