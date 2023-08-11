<?php

session_start();

require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'/vendor/autoload.php';

spl_autoload_register(function ($classname) {
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('App', 'app', $classname) . '.php';
});

define('__VIEW__', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);

$router = new \App\Router();

$router->register('get', '/users', function () {
    return file_get_contents('https://randomuser.me/api');
})
    ->register('get', '/login', [\App\Controllers\LoginController::class, 'loginForm'])
    ->register('post', '/login', [\App\Controllers\LoginController::class, 'login'])
    ->register('get', '/logout', [\App\Controllers\LoginController::class, 'logout'])
    ->register('get', '/admin', [\App\Controllers\AdminController::class, 'index'])
    ->register('post', '/users', [\App\Controllers\AdminController::class, 'destroy'])
    ->register('post', '/verify', [\App\Controllers\AdminController::class, 'verifiyEmail']);


echo $router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

//require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'app/simple-user-sqlite.php';