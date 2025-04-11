<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin/sidebar.css">
</head>

<body>

    <!-- Sidebar -->
    <?php include 'app/views/Utils/Sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h4>Movie Manager</h4>
        </div>

        <h2>Manage Movies</h2>

        <!-- Add Movie Button -->
        <a id="add-movie" class="btn btn-primary mb-3">Add Movie</a>

        <!-- Table to display movies -->
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
                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?php echo $movie['id']; ?></td>
                        <td><?php echo $movie['title']; ?></td>
                        <td><?php echo $movie['description']; ?></td>
                        <td><?php echo $movie['releaseYear']; ?></td>
                        <td><?php echo $movie['director']; ?></td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMovieModal<?php echo $movie['id']; ?>" id="openEditMovieModal" data-id="<?php echo $movie['id']; ?>">Edit</button>
                            <a href="/Movie_Project/Movie/delete/<?php echo $movie['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="/Movie_Project/Movie/manageMovie?page=<?php echo $page - 1; ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link" href="/Movie_Project/Movie/manageMovie?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="/Movie_Project/Movie/manageMovie?page=<?php echo $page + 1; ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal Add Movie -->
    <div class="modal fade" id="addMovieModal" tabindex="-1" aria-labelledby="addMovieModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMovieModalLabel">Add New Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-content-body">
                    <!-- Content will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Movie -->
    <?php foreach ($movies as $movie): ?>
        <div class="modal fade" id="editMovieModal<?php echo $movie['id']; ?>" tabindex="-1" aria-labelledby="editMovieModalLabel<?php echo $movie['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMovieModalLabel<?php echo $movie['id']; ?>">Edit Movie: <?php echo $movie['title']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-content-body<?php echo $movie['id']; ?>">
                        <!-- Content will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Custom JS to handle dynamic content loading -->
    <script>
        // When Add Movie button is clicked
        document.getElementById('add-movie').addEventListener('click', function() {
            fetch('/Movie_Project/Movie/add') // Adjust with the correct URL path
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modal-content-body').innerHTML = html;
                    var myModal = new bootstrap.Modal(document.getElementById('addMovieModal'));
                    myModal.show();
                })
                .catch(error => {
                    console.log('Error loading add movie content:', error);
                });
        });

        // When Edit Movie button is clicked
        document.querySelectorAll('[id^="openEditMovieModal"]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default action

                var movieId = this.getAttribute('data-id'); // Get movie ID from data-id attribute
                fetch('/Movie_Project/Movie/update/' + movieId) // Adjust with correct URL path
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modal-content-body' + movieId).innerHTML = html;
                    })
                    .catch(error => {
                        alert('Error loading movie form.');
                        console.error('Error loading movie form:', error);
                    });
            });
        });
    </script>
</body>

</html>
