<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use League\ISO3166\ISO3166;
use Src\Routing\Router;
use Src\View\View;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$view = new View();
$countries = new ISO3166();
$router = new Router($view, $countries);
$router->startRouter();