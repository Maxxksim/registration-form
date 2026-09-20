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
        [$preparedData, $preparedRules] = $this->removeUnchangedData(array_merge($this->getData(), $this->files), $rules);

        return $this->validator->validate($preparedData, $preparedRules);
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
}