<?php

declare(strict_types=1);

namespace Src\Validator;

use DateTime;
use Exception;
use finfo;
use League\ISO3166\ISO3166;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\ValidationResult;
use Src\Database\Db;

class Validator
{
    private array $allowedFields = ['first_name', 'last_name', 'birthdate', 'report_subject', 'country', 'phone', 'email', 'company', 'position', 'about_me'];

    public function __construct(private Db $db, private PhoneNumberUtil $phoneNumberUtil, private ISO3166 $countries)
    {

    }

    public function validate(array $data, array $rules): array
    {
        $errors = [];
        $validatedData = [];
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

                if (!is_array($data[$field])) {
                    $data[$field] = trim($data[$field]);
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
                    'unique' => $this->validateUnique($data[$field], $field, $params) ? 'unique' : null,
                    'email' => !filter_var($data[$field], FILTER_VALIDATE_EMAIL) ? 'email' : null,
                    'int' => !ctype_digit($data[$field]) ? 'int' : null,
                    'phone' => !($result = $this->validatePhoneNumber($data[$field]))['result'] ? ['phone', $result] : null,
                    'date' => !$this->validateDate($data[$field]) ? 'date' : null,
                    'birthdate' => !$this->validateBirthdate($data[$field]) ? 'birthdate' : null,
                    'country.exists' => !$this->validateCountryExists($data[$field]) ? 'country' : null,
                };

                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
            if (!isset($errors[$field]) && isset($data[$field])) {
                $validatedData[$field] = $data[$field];
            }
        }

        return ['validatedData' => $validatedData, 'errors' => $errors];
    }

    private function validateCountryExists(string $value): bool
    {
        return in_array(mb_strtolower($value), array_map('mb_strtolower', array_column($this->countries->all(), 'name')), true);
    }

    private function validatePhoneNumber(string $phoneNumber): bool|array
    {
        try {
            $parsedNumber = $this->phoneNumberUtil->parse($phoneNumber);
        } catch (Exception $e) {
            return [
                'result' => false,
                'typError' => 'invalid'
            ];
        }

        $possibleReason = $this->phoneNumberUtil->isPossibleNumberWithReason($parsedNumber);

        if ($possibleReason !== ValidationResult::IS_POSSIBLE) {
            return match ($possibleReason) {
                ValidationResult::TOO_SHORT => [
                    'result' => false,
                    'typError' => 'short'
                ],
                ValidationResult::TOO_LONG => [
                    'result' => false,
                    'typError' => 'long'
                ]
            };
        }

        if (!$this->phoneNumberUtil->isValidNumber($parsedNumber)) {
            return [
                'result' => false,
                'typError' => 'invalid'
            ];
        }

        return ['result' => true];
    }

    private function getExamplePhoneNumber($countryCode): string
    {
        $example = $this->phoneNumberUtil->getExampleNumber($countryCode);
        return $this->phoneNumberUtil->format($example, PhoneNumberFormat::INTERNATIONAL);
    }

    private function validateUnique(string $value, string $field, string $table): bool
    {
        if (in_array($field, $this->allowedFields)) {
            $stmt = $this->db->pdo->prepare("SELECT * FROM $table WHERE $field = :value");
            $stmt->bindParam(':value', $value);
            $stmt->execute();

            if ($stmt->fetch()) {
                return true;
            }
        }

        return false;
    }

    private function validateBirthdate(string $birthdate): bool
    {
        $birthdate = new DateTime($birthdate);
        $currentDate = new DateTime(date('Y-m-d'));

        if ($birthdate > $currentDate) {
            return false;
        }

        return true;
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



