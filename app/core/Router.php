<?php
namespace Ltech\WebTimbangan\core;
use Ltech\WebTimbangan\controllers\ErrorHandlingCountroller;

class Router {
    private static array $routes = [];

    public static function add(string $method, 
                                string $path, 
                                string $controller, 
                                string $function,
                                array $middlewares = []): void {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middleware' => $middlewares
        ];
    }

    public static function run():void{
        $path = '/';
        // menangani routing dengan path_info
        if (isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        }
        // menangkap request method
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            $pattern = '#^' . $route['path'] . '$#';
            if (preg_match($pattern, $path, $variables) && $route['method'] === $method) {
                // call midleware
                foreach ($route['middleware'] as $middleware) {
                    $instance = new $middleware;
                    $instance->before();
                }
                // panggil controller dan function
                $function = $route['function'];
                $controller = new $route['controller'];
                
                array_shift($variables);
                call_user_func_array([$controller, $function], $variables);
                return;
            }
        }

        http_response_code(404);
        $error = new ErrorHandlingCountroller();
        $error->index();
    }
}
?>
