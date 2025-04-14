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
    public function facebookLogin() {
        session_start();
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['accessToken']) || empty($data['email']) || empty($data['name'])) {
            error_log("Facebook login failed: Missing data - " . json_encode($data));
            echo json_encode(['success' => false, 'message' => 'Invalid or missing data']);
            return;
        }
        $appId = '658420980224756';
        $appSecret = 'c5c29535c6ba954fde3391cbaa9623e2';
        $url = "https://graph.facebook.com/debug_token?input_token={$data['accessToken']}&access_token={$appId}|{$appSecret}";

        $response = @file_get_contents($url);
        if ($response === false) {
            error_log("Facebook login failed: Token verification failed");
            echo json_encode(['success' => false, 'message' => 'Failed to verify token']);
            return;
        }

        $tokenData = json_decode($response, true);

        if (!$tokenData['data']['is_valid']) {
            error_log("Facebook login failed: Invalid token");
            echo json_encode(['success' => false, 'message' => 'Invalid access token']);
            return;
        }

        $user = $this->user->getUserByEmail($data['email']);

        if (!$user) {
            $result = $this->user->createFacebookUser($data['name'], $data['email'], $data['id']);
            if ($result) {
                $user = $this->user->getUserByEmail($data['email']);
                error_log("Facebook user created: email={$data['email']}, name={$data['name']}, user_id={$user['id']}");
            } else {
                error_log("Facebook user creation failed: email={$data['email']}");
                echo json_encode(['success' => false, 'message' => 'Failed to create user']);
                return;
            }
        }

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'] ?? 'user';
            session_regenerate_id(true);
            error_log("Facebook login success: user_id={$user['id']}, user_name={$user['name']}");
            echo json_encode(['success' => true, 'session' => [
                'user_id' => $_SESSION['user_id'],
                'user_name' => $_SESSION['user_name'],
                'role' => $_SESSION['role']
            ]]);
        } else {
            error_log("Facebook login failed: User not found after creation - email={$data['email']}");
            echo json_encode(['success' => false, 'message' => 'User data not found']);
        }
    }

    public function getUserRole() {
        session_start();
        if (isset($_SESSION['role'])) {
            echo json_encode(['role' => $_SESSION['role']]);
        } else {
            echo json_encode(['role' => 'user']);
        }
    }
}
?>