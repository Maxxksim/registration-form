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

    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {

            foreach ($ruleSet as $rule) {
                [$type, $params] = array_pad(explode(':', $rule, 2), 2, null);

                if (in_array($type, ['type', 'size'], true) && isset($errors[$field])) {
                    continue;
                }

                if ($type === 'required' && !isset($data[$field])) {
                    $errors[$field] = 'required';
                    break;
                } else if (!isset($data[$field])) {
                    break;
                }

                $error = match ($type) {
                    'required' => $data[$field] === '' ? 'required' : null,
                    'file' => $this->validateFile($data[$field]['tmp_name']) ? 'file' : null,
                    'type' => !$this->validateFileType($data[$field]['tmp_name'], explode(',', $params)) ? ['type', explode(',', $params)] : null,
                    'size' => !$this->validateFileSize($data[$field]['tmp_name'], (int)$params) ? ['size', (int)$params] : null,
                    'length' => mb_strlen($data[$field]) !== (int)$params ? ['length', (int)$params] : null,
                    'min' => mb_strlen($data[$field]) < (int)$params ? ['min', (int)$params] : null,
                    'max' => mb_strlen($data[$field]) > (int)$params ? ['max', (int)$params] : null,
                    'string' => !is_string($data[$field]) ? 'string' : null,
                    'unique' => $this->validateUnique($data[$field], $field) ? 'unique' : null,
                    'email' => !filter_var($data[$field], FILTER_VALIDATE_EMAIL) ? 'email' : null,
                    'int' => !ctype_digit($data[$field]) ? 'int' : null,
                    'country-code' => !str_starts_with($data[$field], $params) ? ['country-code', $params] : null,
                    'date' => !$this->validateDate($data[$field]) ? 'date' : null,
                    default => null,
                };

                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }

        return ['validatedData' => $data, 'errors' => $errors];
    }

    private function validateUnique(string $value, string $field): bool
    {
        if (in_array($field, $this->allowedFields)) {
            $stmt = $this->db->pdo->prepare("SELECT * FROM members WHERE $field = :value");
            $stmt->bindParam(':value', $value);
            $stmt->execute();

            if ($stmt->fetch()) {
                return true;
            }
        }

        return false;
    }

    private function validateFile(string $path): bool
    {
        if (!is_uploaded_file($path)) {
            return true;
        }

        return false;
    }

    private function validateDate(string $date): bool
    {
        $result = date_create_from_format('Y-m-d', $date);

        if ($result && $result->format('Y-m-d') === $date) {
            return true;
        }

        return false;
    }

    private function validateFileSize(string $path, int $maxK): bool
    {
        if (filesize($path) > $maxK * 1024) {
            return false;
        }
        return true;
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



