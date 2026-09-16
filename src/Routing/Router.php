<?php

declare(strict_types=1);

namespace Src\Routing;

use League\ISO3166\ISO3166;
use Src\Database\Db;
use Src\Request\Request;
use Src\Validator\Validator;
use Src\View\View;

class Router
{
    private array $routes = [];

    public function __construct(private View $view, private ISO3166 $countries, private Request $request, private Db $db)
    {
        $this->initRoutes();
    }

    public function startRouter(): void
    {

        if (!$route = $this->findRoute($this->request->getRequestMethod(), $this->request->getRequestUri())) {
            echo '404';
            exit;
        }

        if (is_array($route->getCallback())) {
            [$controller, $callback] = $route->getCallback();
            $controller = new $controller($this->view, $this->countries, $this->request->validator, $this->request, $this->db);
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