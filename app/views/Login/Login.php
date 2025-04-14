<?php
session_start();
define('BASE_URL', '/Movie_Project');
require_once __DIR__ . '/../../../vendor/autoload.php';
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: ' . BASE_URL . '/Admin');
    } else {
        header('Location: ' . BASE_URL . '/');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/login/style.css">
</head>
<body>
    <?php include 'app/views/Utils/Navbar.php'; ?>

    <div class="login-container">
        <div class="logo-container mb-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/69/IMDB_Logo_2016.svg" alt="IMDb Logo" class="logo-img" />
        </div>
        <h2 class="text-2xl font-bold text-center mb-4">Đăng nhập</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger text-center' role='alert'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success text-center' role='alert'>" . htmlspecialchars($_SESSION['success']) . "</div>";
            unset($_SESSION['success']);
        }
        ?>

        <form action="<?php echo BASE_URL; ?>/Login/login" method="POST" id="login-form">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login mb-3" id="login-btn">Đăng nhập</button>

            <div class="text-center mt-3">
                <button type="button" class="btn btn-primary" id="fb-login-btn" onclick="loginWithFacebook()">Đăng nhập bằng Facebook</button>
            </div>
            <p class="text-center">
                Bạn chưa có tài khoản? 
                <a href="<?php echo BASE_URL; ?>/Register" class="text-yellow-500">Đăng ký</a>
            </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '658420980224756',
                xfbml: true,
                version: 'v22.0'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Handle form submission
        const loginForm = document.getElementById('login-form');
        const loginBtn = document.getElementById('login-btn');
        loginForm.addEventListener('submit', function(e) {
            loginBtn.disabled = true;
            loginBtn.textContent = 'Đang xử lý...';
            console.log('Regular login submitted:', { email: document.getElementById('email').value }); // Debug
        });

        // Reset button if page reloads with error
        window.addEventListener('load', function() {
            if (document.querySelector('.alert-danger')) {
                loginBtn.disabled = false;
                loginBtn.textContent = 'Đăng nhập';
            }
        });

        function loginWithFacebook() {
            const fbButton = document.getElementById('fb-login-btn');
            fbButton.disabled = true;
            fbButton.textContent = 'Đang xử lý...';

            FB.login(function(response) {
                if (response.authResponse) {
                    const accessToken = response.authResponse.accessToken;
                    FB.api('/me', {fields: 'name,email'}, function(userInfo) {
                        console.log('Facebook user info:', userInfo); // Debug
                        fetch('<?php echo BASE_URL; ?>/Login/facebookLogin', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({
                                id: userInfo.id,
                                name: userInfo.name,
                                email: userInfo.email,
                                accessToken: accessToken
                            }),
                            credentials: 'include'
                        })
                        .then(response => response.json())
                        .then(data => {
                            fbButton.disabled = false;
                            fbButton.textContent = 'Đăng nhập bằng Facebook';
                            console.log('Facebook login response:', data); // Debug
                            if (data.success) {
                                fetch('<?php echo BASE_URL; ?>/Login/getUserRole', {
                                    method: 'GET',
                                    credentials: 'include'
                                })
                                .then(response => response.json())
                                .then(roleData => {
                                    console.log('Role data:', roleData); // Debug
                                    if (roleData.role === 'admin') {
                                        window.location.href = "<?php echo BASE_URL; ?>/Admin";
                                    } else {
                                        window.location.href = "<?php echo BASE_URL; ?>/";
                                    }
                                });
                            } else {
                                alert('Đăng nhập thất bại: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            fbButton.disabled = false;
                            fbButton.textContent = 'Đăng nhập bằng Facebook';
                            console.error('Fetch error:', error); // Debug
                            alert('Lỗi kết nối: ' + error.message);
                        });
                    });
                } else {
                    fbButton.disabled = false;
                    fbButton.textContent = 'Đăng nhập bằng Facebook';
                    alert('Người dùng đã huỷ đăng nhập.');
                }
            }, {scope: 'email'});
        }
    </script>
</body>
</html>