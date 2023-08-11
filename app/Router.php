<?php
declare(strict_types=1);

namespace App;
class Router{

    private array $routes = [];

    public function register(string $method, string $url, callable|array $action): static
    {
       [$method, $url] =  $this->prepare($method, $url);
        $this->routes[$method][$url] = $action;

        return $this;
    }

    public function resolve(string $method, string $url)
    {
        [$method, $url] =  $this->prepare($method, $url);
        $action = $this->routes[$method][$url] ?? null;

        if(!$action){
            throw new \Exception('Route not found!');
        }

        if(is_callable($action)){
            return call_user_func_array($action, []);
        }

        if(is_array($action)){
            [$class, $method] = $action;
            if(class_exists($class)){
                $class = new $class;
                if(method_exists($class, $method)){
                   return call_user_func([$class,$method]);
                }
            }
        }

        throw new \Exception('Something worng!');
    }

    private function prepare($method, $url){
        return [
             strtolower($method),
            rtrim($url,'/')
        ];
    }
}