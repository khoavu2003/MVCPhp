<?php
// Autoload các lớp
require_once 'app/core/Router.php';

// Chạy Router để phân tích và điều hướng
App\Core\Router::handleRequest();
