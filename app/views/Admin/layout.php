<!-- layout.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            color: white;
            position: fixed;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #444;
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        .header {
            background-color: #222;
            color: white;
            padding: 10px;
        }

        .table th,
        .table td {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="p-3">
            <h4>Admin Dashboard</h4>
            <hr>
            <a href="#" id="manage-movies">Manage Movies</a>
            <a href="#" id="add-movie">Add Movie</a>
            <a href="#">Settings</a>
            <a href="#">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h4>Welcome to Admin Dashboard</h4>
        </div>

        <!-- Page content will go here -->
        <div id="content">
            <?php echo $content; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
