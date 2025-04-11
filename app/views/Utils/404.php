<?php
// Kiểm tra quyền truy cập (thêm điều kiện kiểm tra quyền trong dự án của bạn)
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Nếu không phải admin thì hiển thị trang chặn quyền
    $accessDenied = true;
} else {
    // Người dùng có quyền truy cập
    $accessDenied = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .access-denied-container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 80%;
            max-width: 600px;
        }

        .access-denied-container h1 {
            color: #dc3545;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .access-denied-container p {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .access-denied-container .btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .access-denied-container .btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php if ($accessDenied): ?>
    <!-- Access Denied Message -->
    <div class="access-denied-container">
        <h1>Access Denied</h1>
        <p>You do not have permission to view this page.</p>
        <a href="/Movie_Project/Movie" class="btn">Go Back to Home</a>
    </div>
<?php else: ?>
    <!-- Content for authorized users -->
    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
    </div>
<?php endif; ?>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
