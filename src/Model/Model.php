<?php

declare(strict_types=1);

namespace Src\Model;

use Src\Database\Db;

class Model
{
    public function __construct(protected Db $db)
    {

    }

    protected function insert(string $table, array $data): void
    {
        $fields = array_keys($data);
        $columns = implode(', ', $fields);
        $binds = implode(', ', array_map(fn($field) => ":$field", $fields));
        $sql = "INSERT INTO $table ($columns) VALUES $binds";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute();
    }
}