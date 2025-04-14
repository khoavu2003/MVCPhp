<?php
// Include file Database.php để kết nối CSDL
include_once 'app/config/database.php';
// Include file model User.php
include_once 'app/models/User.php';

class RegisterController {
    private $db;
    private $user;

    public function __construct() {
        // Kết nối đến cơ sở dữ liệu
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }
    public function index() {
        $this->showRegisterForm(); // Gọi phương thức showLoginForm
    }
    // Hiển thị form đăng ký
    public function showRegisterForm() {
        include 'app/views/Login/register.php'; // View đăng ký
    }

    // Xử lý đăng ký
    public function register() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            // Kiểm tra xem mật khẩu có khớp không
            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu không khớp.';
                header('Location: /Movie_Project/Register');
                exit;
            }

            // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
            $this->user->email = $email;
            $existingUser = $this->user->getByEmail();

            if ($existingUser) {
                $_SESSION['error'] = 'Email đã tồn tại.';
                header('Location: /Movie_Project/Register');
                exit;
            }

            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Lưu thông tin người dùng vào cơ sở dữ liệu
            $this->user->name = $name;
            $this->user->password = $hashedPassword;
            $this->user->role = 'user'; // Đặt mặc định là 'user', nếu cần có thể cho phép thay đổi

            if ($this->user->create()) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                header('Location: /Movie_Project/Login');
                exit;
            } else {
                $_SESSION['error'] = 'Đã xảy ra lỗi, vui lòng thử lại.';
                header('Location: /Movie_Project/Register');
                exit;
            }
        }
    }
}
?>
