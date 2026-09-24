<?php

declare(strict_types=1);

namespace Src\Model;

use League\ISO3166\ISO3166;
use libphonenumber\PhoneNumberUtil;
use Src\Database\Db;

class Model
{
    protected static array $fillable = [];
    protected array $attributes = [];

    public function __construct(protected Db $db, protected PhoneNumberUtil $phoneNumberUtil, protected ISO3166 $countries)
    {

    }

    protected function fill(array $data): Model
    {
        if (!empty(static::$fillable)) {
            $this->attributes = array_intersect_key($data, array_flip(static::$fillable));
        }

        return $this;
    }

    protected function create(array $data): static
    {
        $this->fill($data);
        $this->insertData($this->attributes);
        return $this;
    }

    public function update(string $where, string $value, array $data): static
    {
        $this->fill($data);
        $this->updateData($where, $value, $this->attributes);
        return $this;
    }

    private function insertData(array $data): void
    {
        if (empty($data)) {
            return;
        }

        $table = static::$table;
        $fields = array_keys($data);
        $columns = implode(', ', $fields);
        $binds = implode(', ', array_map(fn($field) => ":$field", $fields));
        $sql = "INSERT INTO  $table ($columns) VALUES ($binds)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute($data);
    }


    private function updateData(string $where, string|int $value, array $data): void
    {
        $table = static::$table;
        if (empty($data)) {
            return;
        }

        $preparedFields = [];
        $bindValues = [];
        foreach ($data as $field => $fieldValue) {
            $preparedFields[] = "$field=?";
            $bindValues[] = $fieldValue;
        }

        $binds = implode(', ', $preparedFields);
        $bindValues[] = $value;
        $sql = "UPDATE $table SET $binds WHERE $where=?";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute($bindValues);
    }
}