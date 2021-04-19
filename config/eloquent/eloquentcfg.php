<?php

require __DIR__.'/DB.php';

use app\config\eloquent\DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$container = $app->getContainer();
$settings = $container['settings'];
$logger = $container['logger'];

$DB = new DB();
$DB->addConnection($settings['eloquentcfg']);

// enable events
$DB->setEventDispatcher(new Dispatcher(new Container));

// enable untuk support static
$DB->setAsGlobal();
$DB->bootEloquent();

// disable query log
$DB::connection()->disableQueryLog();

// set logger
$DB->setCustomLogger($logger);

// log query jika logger.stream = 1
if ($settings['logger']['stream'] == 1) {

    $DB->getConnection()->listen(function ($query) use ($logger) {
        $logger->info(json_encode(array(
            'sql' => $query->sql,
            'bind' => $query->bindings,
            'time(ms)' => $query->time
        ), true));
    });
}
