<?php
session_start();
define('BASE_URL', '/Movie_Project'); // Định nghĩa BASE_URL để dễ thay đổi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS tùy chỉnh -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/login/style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include 'app/views/Utils/Navbar.php'; ?>

    <!-- Nội dung chính -->
    <div class="login-container">
        <!-- Logo IMDb -->
        <div class="logo-container mb-4">
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/6/69/IMDB_Logo_2016.svg"
                alt="IMDb Logo"
                class="logo-img"
            />
        </div>

        <!-- Tiêu đề -->
        <h2 class="text-2xl font-bold text-center mb-4">Đăng nhập</h2>

        <!-- Thông báo -->
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

        <!-- Form đăng nhập -->
        <form action="<?php echo BASE_URL; ?>/Login/login" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
                >
            </div>
            <button type="submit" class="btn-login mb-3">Đăng nhập</button>
            <p class="text-center">
                Bạn chưa có tài khoản? 
                <a href="<?php echo BASE_URL; ?>/Register" class="text-yellow-500">Đăng ký</a>
            </p>
        </form>
    </div>

    <!-- Bootstrap JS và Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>