<?php

declare(strict_types=1);

namespace Src\Controller;

use League\ISO3166\ISO3166;
use Src\Config\Config;
use Src\Database\Db;
use Src\Request\Request;
use Src\Storage\Storage;
use Src\Validator\Validator;
use Src\View\View;

class Controller
{
    protected array $steps = ['step1', 'step2', 'stepThanks'];

    public function __construct(protected View $view, protected ISO3166 $countries, protected Request $request, protected Db $db, protected Storage $storage, protected Config $config)
    {

    }

    protected function redirect(string $uri): void
    {
        header("Location: $uri");
        exit;
    }
}