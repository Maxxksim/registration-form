<?php

declare(strict_types=1);

namespace Src\Routing;

use Composer\Autoload\ClassLoader;

class Route
{

    public function __construct(private(set) string $method, private(set) string $path, private array|\Closure $callback)
    {

    }

    public static function post(string $path, array|\Closure $callback): Route
    {
        return new static('POST', $path, $callback);
    }

    public static function get(string $path, array|\Closure $callback): Route
    {
        return new static('GET', $path, $callback);
    }

    public function getCallback(): array|\Closure
    {
        return $this->callback;
    }
}