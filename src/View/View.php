<?php

declare(strict_types=1);

namespace Src\View;

class View
{
    public function view(string $view, array $args = []): void
    {
        extract($args);
        require_once __DIR__ . "/../app/Views/$view.php";
    }
}