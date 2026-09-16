<?php

declare(strict_types=1);

namespace Src\View;

class View
{
    public function view(string $template, array $args = []): void
    {
        $view = $this;
        extract($args);
        require_once __DIR__ . "/../app/Views/$template.php";
    }

    public function component(string $component, array $args = []): void
    {
        extract($args);
        require __DIR__ . "/../app/Views/Components/$component.php";
    }
}