<?php

declare(strict_types=1);
spl_autoload_register(function ($class) {
    $file = dirname(__DIR__) . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Файл для класса {$class} не найден: {$file}");
    }
});
use project\app\Routes\Router;


$requestUri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : null;

$router = new Router();
$router->route($requestUri);