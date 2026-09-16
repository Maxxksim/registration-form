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
        $sql = "INSERT INTO $table ($columns) VALUES ($binds)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute($data);
    }

    protected function update(string $table, array $data, string $where): void
    {
        $fieldsFromModel = static::fields();
        $fields = array_keys($data);
        $preparedFields = [];
        $bindValues = [];
        foreach ($fieldsFromModel as $field) {
            if (in_array($field, $fields)) {
                $preparedFields[] = "$field=?";
                $bindValues[$field] = $data[$field];
            }
        }

        if (empty($preparedFields)) {
            return;
        }

        if (!in_array($where, $fieldsFromModel)) {
            return;
        }

        $binds = implode(', ', $preparedFields);
        $bindValues[] = $where;
        $sql = "UPDATE $table SET $binds WHERE $where=?";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute($bindValues);
    }
}