<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Additional styling for the sidebar and main content */
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

        .table th, .table td {
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
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

    <!-- Main content -->
    <div class="main-content">
        <div class="header">
            <h4>Welcome to Admin Dashboard</h4>
        </div>

        <!-- Content for Manage Movies -->
        <div id="manage-movies-content" class="content">
            <h3>List of Movies</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Release Year</th>
                        <th>Director</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example static data, replace with dynamic data -->
                    <tr>
                        <td>1</td>
                        <td>Movie Title 1</td>
                        <td>Description 1</td>
                        <td>2023</td>
                        <td>Director 1</td>
                        <td>
                            <!-- Link to open the edit modal -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMovieModal1">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Movie Title 2</td>
                        <td>Description 2</td>
                        <td>2024</td>
                        <td>Director 2</td>
                        <td>
                            <!-- Link to open the edit modal -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMovieModal2">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Content for Add Movie -->
        <div id="add-movie-content" class="content" style="display: none;">
            <h3>Add New Movie</h3>
            <form action="/Movie_Project/Movie/add" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="releaseYear" class="form-label">Release Year</label>
                    <input type="number" class="form-control" id="releaseYear" name="releaseYear" required>
                </div>
                <div class="mb-3">
                    <label for="director" class="form-label">Director</label>
                    <input type="text" class="form-control" id="director" name="director" required>
                </div>
                <div class="mb-3">
                    <label for="poster" class="form-label">Poster URL</label>
                    <input type="text" class="form-control" id="poster" name="poster" required>
                </div>
                <div class="mb-3">
                    <label for="bannerImage" class="form-label">Banner Image URL</label>
                    <input type="text" class="form-control" id="bannerImage" name="bannerImage" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Movie</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS to switch between Manage Movies and Add Movie -->
    <script>
        document.getElementById('manage-movies').addEventListener('click', function () {
            document.getElementById('manage-movies-content').style.display = 'block';
            document.getElementById('add-movie-content').style.display = 'none';
        });

        document.getElementById('add-movie').addEventListener('click', function () {
            document.getElementById('manage-movies-content').style.display = 'none';
            document.getElementById('add-movie-content').style.display = 'block';
        });
    </script>

</body>

</html>
