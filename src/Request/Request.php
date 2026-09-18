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
        $allRules = static::rules();
        $fields = array_keys($allRules);
        $preparedData = $this->removeUnchangedData($this->prepareData($this->getData(), $this->files, $fields));
        $rules = $this->prepareRules($preparedData);

        return $this->validator->validate($preparedData, $rules);
    }

    private function prepareData(array $data, array $files, array $fields): array
    {
        $preparedData = [];
        $uploadedFileKey = array_key_first(array_filter($files, fn($file) => $file['error'] !== UPLOAD_ERR_NO_FILE));

        if ($uploadedFileKey) {
            unset($fields[$uploadedFileKey]);
            $preparedData[$uploadedFileKey] = $files[$uploadedFileKey];
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = $data[$field];
            }
        }

        return $preparedData;
    }

    private function prepareRules(array $data): array
    {
        $rules = static::rules();
        foreach ($rules as $field => $ruleSet) {
            if (!isset($data[$field])) {
                unset($rules[$field]);
            }
        }

        return $rules;
    }

    private function removeUnchangedData(array $data): array
    {
        $oldData = $_SESSION['steps']['data'] ?? [];

        if (isset($_SESSION['steps']['data'])) {
            foreach ($data as $field => $value) {
                if (is_array($value)) {
                    continue;
                }

                if ($oldData[$field] === $value) {
                    unset($data[$field]);
                }
            }
        }

        return $data;
    }
}