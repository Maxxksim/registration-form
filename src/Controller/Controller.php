<?php

declare(strict_types=1);

namespace Src\Controller;

use League\ISO3166\ISO3166;
use Src\Request\Request;
use Src\Validator\Validator;
use Src\View\View;

class Controller
{
    public function __construct(protected View $view, protected ISO3166 $countries, protected Validator $validator, protected Request $request)
    {

    }

    protected function redirect(string $uri): void
    {
        header("Location: $uri");
        exit;
    }
}