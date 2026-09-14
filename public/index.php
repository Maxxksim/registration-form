<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Src\Routing\Router;

$router = new Router();

$router->startRouter();