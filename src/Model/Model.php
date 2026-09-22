<?php

declare(strict_types=1);

namespace Src\Model;

use libphonenumber\PhoneNumberUtil;
use Src\Database\Db;

class Model
{
    public function __construct(protected Db $db, protected PhoneNumberUtil $phoneNumberUtil)
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

    public function update(string $where, string $whereValue, array $data): void
    {
        $table = static::$table;
        $fieldsFromModel = static::fields();
        $fields = array_keys($data);
        $preparedFields = [];
        $bindValues = [];
        foreach ($fieldsFromModel as $field) {
            if (in_array($field, $fields, true)) {
                $preparedFields[] = "$field=?";
                $bindValues[] = $data[$field];
            }
        }

        if (empty($preparedFields)) {
            return;
        }

        if (!in_array($where, $fieldsFromModel, true)) {
            return;
        }

        $binds = implode(', ', $preparedFields);
        $bindValues[] = $whereValue;
        $sql = "UPDATE $table SET $binds WHERE $where=?";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute($bindValues);
    }
}