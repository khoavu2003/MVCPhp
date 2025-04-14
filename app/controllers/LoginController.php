<?php
// Include file Database.php để kết nối CSDL
include_once 'app/config/database.php';
// Include file model User.php
include_once 'app/models/User.php';
// Include Google API Client Library
include_once 'vendor/autoload.php';
use Dotenv\Dotenv;

class LoginController
{
    private $db;
    private $user;
    private $googleClient;

    public function __construct()
    {
        // Load .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Kết nối đến cơ sở dữ liệu
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);

        // Cấu hình Google Client
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->googleClient->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->googleClient->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    
    public function index()
    {
        $this->showLoginForm(); // Gọi phương thức showLoginForm
    }
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        include 'app/views/Login/Login.php'; // View đăng nhập
    }

    // Xử lý đăng nhập
    public function login()
    {
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

    public function googleLogin()
    {
        $authUrl = $this->googleClient->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    }

    public function googleCallback()
    {
        session_start();

        var_dump($_GET['code']);


        if (isset($_GET['error'])) {
            error_log('Google Callback - Error from Google: ' . $_GET['error']);
            $_SESSION['error'] = 'Google xác thực thất bại: ' . $_GET['error'];
            header('Location: /Movie_Project/Login');
            exit;
        }

        if (isset($_GET['code'])) {
            try {
                // Ghi log mã code
                error_log('Google Callback - Code received: ' . $_GET['code']);

                // Đổi mã code lấy access token
                $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET['code']);

                // Kiểm tra nếu có lỗi trong token
                if (isset($token['error'])) {
                    error_log('Google Callback - Token error: ' . json_encode($token));
                    $_SESSION['error'] = 'Lỗi xác thực Google: ' . $token['error'];
                    header('Location: /Movie_Project/Login');
                    exit;
                }

                // Ghi log token
                error_log('Google Callback - Token received: ' . json_encode($token));

                $this->googleClient->setAccessToken($token['access_token']);
                $googleService = new Google_Service_OAuth2($this->googleClient);
                $userInfo = $googleService->userinfo->get();

                $email = $userInfo->email;
                $name = $userInfo->name;
                $googleId = $userInfo->id;

                $this->user->google_id = $googleId;
                $existingUser = $this->user->getByGoogleId();

                if ($existingUser) {
                    $_SESSION['user_id'] = $existingUser['id'];
                    $_SESSION['role'] = $existingUser['role'];
                    $_SESSION['user_name'] = $existingUser['name'];
                } else {
                    $this->user->email = $email;
                    $existingUserByEmail = $this->user->getByEmail();

                    if ($existingUserByEmail) {
                        $this->user->id = $existingUserByEmail['id'];
                        $this->user->name = $existingUserByEmail['name'];
                        $this->user->email = $existingUserByEmail['email'];
                        $this->user->password = $existingUserByEmail['password'];
                        $this->user->role = $existingUserByEmail['role'];
                        $this->user->google_id = $googleId;
                        $this->user->update();

                        $_SESSION['user_id'] = $existingUserByEmail['id'];
                        $_SESSION['role'] = $existingUserByEmail['role'];
                        $_SESSION['user_name'] = $existingUserByEmail['name'];
                    } else {
                        $this->user->email = $email;
                        $this->user->name = $name;
                        $this->user->password = null;
                        $this->user->role = 'user';
                        $this->user->google_id = $googleId;

                        if ($this->user->create()) {
                            $newUser = $this->user->getByGoogleId();
                            $_SESSION['user_id'] = $newUser['id'];
                            $_SESSION['role'] = $newUser['role'];
                            $_SESSION['user_name'] = $newUser['name'];
                        } else {
                            $_SESSION['error'] = 'Đã xảy ra lỗi khi tạo tài khoản từ Google.';
                            header('Location: /Movie_Project/Login');
                            exit;
                        }
                    }
                }

                if ($_SESSION['role'] === 'admin') {
                    header('Location: /Movie_Project/Admin');
                } else {
                    header('Location: /Movie_Project/');
                }
                exit;
            } catch (Exception $e) {
                error_log('Google Callback - Exception: ' . $e->getMessage());
                $_SESSION['error'] = 'Lỗi khi xử lý xác thực Google: ' . $e->getMessage();
                header('Location: /Movie_Project/Login');
                exit;
            }
        } else {
            error_log('Google Callback - No code received. Query string: ' . json_encode($_GET));
            $_SESSION['error'] = 'Không nhận được mã xác thực từ Google.';
            header('Location: /Movie_Project/Login');
            exit;
        }
    }

    // Xử lý đăng xuất
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /Movie_Project/Login'); 
        exit;
    }
}
