<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Src\Routing\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$router = new Router();
$router->startRouter();