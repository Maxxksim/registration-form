<?php

declare(strict_types=1);

namespace Src\Routing;

use League\ISO3166\ISO3166;
use Src\Config\Config;
use Src\Database\Db;
use Src\Request\Request;
use Src\Storage\Storage;
use Src\View\View;

class Router
{
    private array $routes = [];

    public function __construct(private View $view, private ISO3166 $countries, private Request $request, private Db $db, private Storage $storage, private Config $config)
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
            $controller = new $controller($this->view, $this->countries, $this->request, $this->db, $this->storage, $this->config);
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