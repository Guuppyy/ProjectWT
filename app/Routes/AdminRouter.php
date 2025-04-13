<?php

namespace project\app\Routes;

use project\app\Controllers\AdminController;

class AdminRouter
{
    private array $routes = [
        'GET:/' => [AdminController::class, 'index'],
        'GET:/list' => [AdminController::class, 'index'],
        'POST:/upload' => [AdminController::class, 'uploadFile'],
        'GET:/delete-file' => [AdminController::class, 'deleteFile'],
        'POST:/create-dir' => [AdminController::class, 'createDirectory'],
        'GET:/delete-dir' => [AdminController::class, 'deleteDirectory'],
    ];

    public function route(string $requestMethod, string $requestUri): void
    {
        $routeKey = $requestMethod . ':' . $requestUri;


        foreach ($this->routes as $pattern => [$controllerClass, $method]) {
            if ($this->matchRoute($routeKey, $pattern)) {
                $controllerInstance = new $controllerClass();
                $controllerInstance->$method();
                return;
            }
        }

        http_response_code(404);
        die("Admin route not found: {$requestMethod} {$requestUri}");
    }

    private function matchRoute(string $routeKey, string $pattern): bool
    {
        if ($routeKey === $pattern) {
            return true;
        }

        [$method, $path] = explode(':', $pattern, 2);
        if (strpos($routeKey, $method.':') === 0) {
            $requestPath = explode('?', substr($routeKey, strlen($method)+1)[0]);
            return $requestPath === $path;
        }

        return false;
    }
}