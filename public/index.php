<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../config.local.php')) {
    require_once __DIR__ . '/../config.local.php';
} else {
    require_once __DIR__ . '/../config.php';
}

use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Runtime;
use WalkWeb\NW\App;

$container = Container::create();
$container->set(Runtime::class, new Runtime());

$router = require __DIR__ . '/../routes/web.php';
$app = new App($router, $container);

$response = $app->handle(Request::fromGlobals());

App::emit($response);
