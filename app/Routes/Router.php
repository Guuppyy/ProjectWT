<?php

namespace project\app\Routes;

use project\app\Controllers\MovieController;


class Router
{
    private array $controllers = [
        ''         => [MovieController::class, 'showMovies'],
    ];

    private AdminRouter $adminRouter;

    public function __construct()
    {
        $this->adminRouter = new AdminRouter();
    }

    public function route(string $requestUri): void
    {
        $basePath = '/project';
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $trimmedUri = preg_replace('#^'.preg_quote($basePath, '#').'#', '', $requestUri);
        $trimmedUri = $trimmedUri === '' ? '/' : $trimmedUri;

        error_log("Processing: {$requestMethod} {$trimmedUri}");

        $path = explode('/', trim(parse_url($trimmedUri, PHP_URL_PATH), '/'));
        $route = $path[0] ?? '';

//        // Проверяем, является ли запрос статическим файлом
//        if (preg_match('/\.(jpg|jpeg|png|gif|css|js|svg)$/i', $requestUri)) {
//            return; // Пропускаем обработку
//        }

        if ($route === 'admin') {
            $adminPath = '/' . implode('/', array_slice($path, 1));
            $this->adminRouter->route($requestMethod, $adminPath);
            return;
        }

        if (isset($this->controllers[$route])) {
            [$controllerClass, $method] = $this->controllers[$route];
            $controllerInstance = new $controllerClass();

            if (method_exists($controllerInstance, $method)) {
                $controllerInstance->$method();
                return;
            }
        }

        http_response_code(404);
        die("Маршрут не найден: {$requestMethod} {$requestUri}");
    }
}