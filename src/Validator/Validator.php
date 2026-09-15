<?php

declare(strict_types=1);

namespace Src\Validator;

use finfo;
use Src\Database\Db;

class Validator
{
    private array $allowedFields = ['firstName', 'lastName', 'birthdate', 'reportSubject', 'country', 'phone', 'email', 'company', 'position', 'aboutMe'];

    public function __construct(private Db $db)
    {

    }

    public function validate(array $fields, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            foreach ($ruleSet as $rule) {
                [$type, $params] = array_pad(explode(':', $rule, 2), 2, null);

                $error = match ($type) {
                    'required' => trim((string)($fields[$field] ?? '')) === '' ? "Field $field is required." : null,
                    'file' => !is_file($fields[$field]) ? "Field $field must be file." : null,
                    'type' => !$this->validateFileType($fields[$field], explode(',', $params)) ? "File has invalid type." : null,
                    'size' => $this->validateFileSize($fields[$field], (int)$params) ? "File is too large." : null,
                    'length' => mb_strlen($fields[$field]) !== (int)$params ? "Must have $params chars." : null,
                    'min' => mb_strlen($fields[$field]) < (int)$params ? "Must have more than $params chars." : null,
                    'max' => mb_strlen($fields[$field]) > (int)$params ? "Must have less than $params chars." : null,
                    'string' => !is_string($fields[$field]) ? "Field $field must be string." : null,
                    'unique' => $this->validateUnique($field, $fields[$field]) ? "Field $field must be unique." : null,
                    'email' => !filter_var($fields[$field], FILTER_VALIDATE_EMAIL) ? "Field $field must be email." : null,
                    default => null,
                };

                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }

        return $errors;
    }

    private function validateUnique(string $field, string $value): bool
    {
        if (in_array($field, $this->allowedFields)) {
            $stmt = $this->db->pdo->prepare("SELECT * FROM persons WHERE $field = :value");
            $stmt->bindParam(':value', $value);
            $stmt->execute();

            if ($stmt->fetch()) {
                return true;
            }
        }

        return false;
    }

    private function validateFileSize(string $path, int $maxMb): bool
    {
        if (filesize($path) > $maxMb * 1024 * 1024) {
            return true;
        }
        return false;
    }

    private function validateFileType(string $path, array $allowedTypes): bool
    {
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $fileInfo->file($path);
        if (in_array($mime, $allowedTypes)) {
            return true;
        }

        return false;
    }
}



