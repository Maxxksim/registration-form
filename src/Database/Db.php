<?php

declare(strict_types=1);

namespace Src\Database;

use PDOException;

class Db
{
    private(set) \PDO $pdo;
    public function __construct()
    {
        try {

            $servername = $_ENV['DB_SERVERNAME'];
            $dbname = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];

            $this->pdo = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}
