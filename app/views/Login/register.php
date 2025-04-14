<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .register-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .logo-container {
            text-align: center;
        }

        .logo-img {
            width: 100px;
        }

        .btn-register {
            background-color: #f5c518;
            color: black;
            font-weight: bold;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background-color: #e3b814;
        }

        .text-yellow-500 {
            color: #f5c518;
            text-decoration: none;
        }

        .text-yellow-500:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'app/views/Utils/Navbar.php'; ?>

<div class="register-container">
    <div class="logo-container mb-4">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/69/IMDB_Logo_2016.svg" alt="IMDb Logo" class="logo-img" />
    </div>
    <h3 class="text-center mb-4">Đăng ký</h3>

    <!-- Thông báo lỗi -->
    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger text-center'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<div class='alert alert-success text-center'>" . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="/Movie_Project/Register/register" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
        </div>
        <button type="submit" class="btn-register mb-3">Đăng ký</button>
        <p class="text-center">
            Đã có tài khoản? 
            <a href="/Movie_Project/Login" class="text-yellow-500">Đăng nhập</a>
        </p>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
