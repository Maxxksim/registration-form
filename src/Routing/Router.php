<?php

declare(strict_types=1);

namespace Src\Routing;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->initRoutes();
    }

    public function startRouter(): void
    {

        if (!$route = $this->findRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'])) {
            echo '404';
            exit;
        }

        if (is_array($route->getCallback())) {
            [$controller, $callback] = $route->getCallback();
            $controller = new $controller();
            $controller->$callback();
        } else {
            $route->getCallback()();
        }


    }

    private function initRoutes(): void
    {
        foreach (require_once 'routes.php' as $route) {
            $this->routes[$route->method][$route->path] = $route;
        }
    }

    private function findRoute(string $method, string $path): ?Route
    {
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }

        return null;
    }
}