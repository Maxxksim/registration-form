<?php

declare(strict_types=1);

namespace Src\Request;

use Src\Validator\Validator;

class Request
{
    public function __construct(private(set) Validator $validator, public array $get, public array $post, public array $files, public array $server)
    {
    }

    public function getRequestMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getRequestUri(): string
    {
        return $this->server['REQUEST_URI'];
    }

    private function getData(): array
    {
        if ($this->getRequestMethod() === 'POST') {
            return $this->post;
        }

        return $this->get;
    }

    public function validated(): array
    {
        $rules = static::rules();
        [$preparedData, $preparedRules] = $this->removeUnchangedData(array_merge($this->getData(), array_filter($this->files, fn($file) => $file['error'] !== UPLOAD_ERR_NO_FILE)), $rules);
        $validatedData = $this->validator->validate($preparedData, $preparedRules, $_SESSION);

        if (!empty($validatedData['errors'])) {
            foreach ($validatedData['errors'] as $field => $error) {
                $validatedData['errors'][$field] = $this->getErrorMessage($this->parseField($field), $error);
            }
        }

        return $validatedData;
    }

    private function removeUnchangedData(array $data, array $rules): array
    {
        $oldData = $_SESSION['steps']['data'] ?? [];

        if (isset($_SESSION['steps']['data'])) {
            foreach ($data as $field => $value) {
                if (isset($_SESSION['steps']['data'][$field])) {

                    if (is_array($value)) {
                        continue;
                    }

                    if ($oldData[$field] === $value) {
                        unset($data[$field]);
                        unset($rules[$field]);
                    }
                }
            }
        }
        return [$data, $rules];
    }

    private function parseField(string $unparsedField): string
    {

        $parts = explode('_', $unparsedField);

        if (count($parts) > 1) {
            $unparsedField = implode(' ', $parts);
        } else {
            $unparsedField = $parts[0];
        }

        return $unparsedField;
    }


    private function getErrorMessage(string $field, string|array $error): string
    {
        [$type, $param] = is_array($error) ? $error : [$error, null];
        $messages = static::messages();

        if (is_array($messages)) {
            return $messages["$field.$type"] ?? self::messages()($field, $type, $param);
        }
        return $messages($field, $type, $param);
    }

    protected static function messages(): array|\Closure
    {
        return fn(string $field, string $type, mixed $param) => match ($type) {
            'required' => "Please enter your $field",
            'string' => "$field must be text",
            'email' => 'Please enter a valid email address, e.g. example@domain.com',
            'unique' => "This $field is already taken",
            'int' => "$field must contain numbers only",
            'date' => 'Please enter a valid date in the format YYYY-MM-DD',
            'file' => 'File upload failed. Please try again',
            'length' => "$field must be $param characters",
            'min' => "$field must be at least $param characters",
            'max' => "$field must not exceed $param characters",
            'type' => 'Unsupported file type. Allowed: ' . (is_array($param)
                    ? implode(', ', array_map(fn($value) => explode('/', $value)[1], $param))
                    : $param),
            'size' => "File size must be $param KB or less",
            'birthdate' => 'Birth date cannot be in the future',
            'phone' => match ($param['typError']) {
                'format' => "Invalid phone number format",
                'long' => "Your number is too long",
                'short' => "Your number is too short",
                'invalid' => 'Please enter a valid phone number'
            }
        };
    }
}