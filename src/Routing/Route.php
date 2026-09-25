<?php

declare(strict_types=1);

namespace Src\Routing;

class Route
{

    public function __construct(private(set) string $method, private(set) string $path, private $callback)
    {

    }

    public static function post(string $path, callable $callback): Route
    {
        return new static('POST', $path, $callback);
    }

    public static function get(string $path, callable $callback): Route
    {
        return new static('GET', $path, $callback);
    }

    public function getCallback(): callable|null|array
    {
        return $this->callback;
    }
}