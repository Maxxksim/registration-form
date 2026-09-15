<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Src\Database\Db;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = new Db();

if (!$migrations = glob(__DIR__ . '/../src/Database/migrations/*.sql')) {
    echo 'No found migrations';
    exit(1);
};

natsort($migrations);

try {
    foreach ($migrations as $migration) {
        $sql = file_get_contents($migration);
        $db->pdo->exec($sql);
    }
} catch (PDOException $e) {
    throw new PDOException($e->getMessage());
}
