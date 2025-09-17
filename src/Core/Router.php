<?php


namespace OnePieceTCGCollect\src\Core;
session_start();
class Router
{
    public function routes()
    {
        $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'Home';
        $controllerClass = '\\OnePieceTCGCollect\\src\\Controllers\\' . htmlspecialchars($controllerName) . 'Controller';

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Contrôleur introuvable : $controllerClass";
            return;
        }

        $action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'index';

        $controller = new $controllerClass();

        if (method_exists($controller, $action)) {
            $params = [];
            if (isset($_GET['id'])) {
                $params[] = (int) htmlspecialchars($_GET['id']);
            }
            call_user_func_array([$controller, $action], $params);
        } else {
            http_response_code(404);
            echo "Méthode $action inexistante dans $controllerClass";
        }
    }
}
