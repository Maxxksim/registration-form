<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use League\ISO3166\ISO3166;
use Src\Database\Db;
use Src\Routing\Router;
use Src\Validator\Validator;
use Src\View\View;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$view = new View();
$countries = new ISO3166();
$db = new Db();
$validator = new Validator($db);
$router = new Router($view, $countries, $validator);

$router->startRouter();