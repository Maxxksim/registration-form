<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use League\ISO3166\ISO3166;
use Src\Config\Config;
use Src\Database\Db;
use Src\Request\Request;
use Src\Routing\Router;
use Src\Storage\Storage;
use Src\Validator\Validator;
use Src\View\View;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$view = new View();
$countries = new ISO3166();
$db = new Db();
$validator = new Validator($db);
$request = new Request($validator, $_GET, $_POST, $_FILES, $_SERVER);
$storage = new Storage();
$config = new Config();
$router = new Router($view, $countries, $request, $db, $storage, $config);

$router->startRouter();