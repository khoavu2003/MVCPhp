<?php
namespace App\Core;

class Router {

    // Phương thức để phân tích và điều hướng
    public static function handleRequest() {
        // Lấy URL từ query string
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        // Mặc định controller là MovieController và action là index
        $controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'MovieController';
        $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

        // Kiểm tra nếu controller không tồn tại
        $controllerPath = 'app/controllers/' . $controllerName . '.php';
        if (!file_exists($controllerPath)) {
            die('Controller not found: ' . $controllerName);
        }

        require_once $controllerPath;

        // Kiểm tra nếu action không tồn tại
        $controller = new $controllerName();
        if (!method_exists($controller, $action)) {
            die('Action not found: ' . $action);
        }

        // Gọi action
        call_user_func_array([$controller, $action], array_slice($url, 2));
    }
}
