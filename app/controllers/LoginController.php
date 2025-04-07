<?php
// Include file Database.php để kết nối CSDL
include_once 'app/config/database.php';
// Include file model User.php
include_once 'app/models/User.php';

class LoginController {
    private $db;
    private $user;

    public function __construct() {
        // Kết nối đến cơ sở dữ liệu
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }
    public function index() {
        $this->showLoginForm(); // Gọi phương thức showLoginForm
    }
    // Hiển thị form đăng nhập
    public function showLoginForm() {
        include 'app/views/Login/Login.php'; // View đăng nhập
    }

    // Xử lý đăng nhập
    public function login() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy thông tin người dùng từ form đăng nhập
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Kiểm tra người dùng trong cơ sở dữ liệu
            $this->user->email = $email;
            $user = $this->user->getByEmail(); // Giả sử `getByEmail` trả về người dùng theo email

            // Kiểm tra mật khẩu
            if ($user && password_verify($password, $user['password'])) {
                // Lưu thông tin người dùng vào session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];

                // Phân quyền
                if ($user['role'] === 'admin') {
                    header('Location: /Movie_Project/Admin'); // Chuyển hướng đến trang admin nếu là admin
                } else {
                    header('Location: /Movie_Project/'); // Chuyển hướng đến trang người dùng nếu là user
                }
                exit;
            } else {
                // Thông báo lỗi nếu thông tin không đúng
                $_SESSION['error'] = 'Invalid email or password.';
                header('Location: /Movie_Project/Login');
                exit;
            }
        } else {
            // Hiển thị form đăng nhập nếu không phải yêu cầu POST
            include 'app/views/Login/Login.php';
        }
    }

    // Xử lý đăng xuất
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /Movie_Project/Login'); // Chuyển hướng đến trang đăng nhập sau khi đăng xuất
        exit;
    }
}
?>
