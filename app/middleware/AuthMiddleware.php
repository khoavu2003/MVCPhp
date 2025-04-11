<?php


class AuthMiddleware {

    // Kiểm tra quyền truy cập của admin
    public static function checkAdmin() {
        // Khởi tạo session nếu chưa được khởi tạo
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Bắt đầu session
        }

        // Kiểm tra nếu chưa đăng nhập hoặc không phải admin, chuyển hướng về trang chủ
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'You do not have permission to access this page!';
            header('Location: /Movie_Project/app/views/Utils/404.php');  // Hoặc chuyển hướng đến trang đăng nhập
            exit;
        }
    }
}
