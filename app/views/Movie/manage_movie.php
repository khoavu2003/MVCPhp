<?php
// manage_movie.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Movies</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="../public/admin/css/main.css">
</head>
<body>
    <!-- Include Sidebar -->
    <?php include 'app/views/Utils/Sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h4>Movie Manager</h4>
            <a id="add-movie" class="btn btn-primary"><i class="fas fa-plus"></i> Add Movie</a>
        </div>

        <!-- Movies Table -->
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
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMovieModal<?php echo $movie['id']; ?>" data-id="<?php echo $movie['id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                            <a href="/Movie_Project/Movie/delete/<?php echo $movie['id']; ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
                <div class="modal-body" id="modal-content-body"></div>
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
                    <div class="modal-body" id="modal-content-body<?php echo $movie['id']; ?>"></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add Movie Modal
        document.getElementById('add-movie').addEventListener('click', function() {
            fetch('/Movie_Project/Movie/add')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modal-content-body').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('addMovieModal')).show();
                })
                .catch(error => console.error('Error loading add movie content:', error));
        });

        // Edit Movie Modal
        document.querySelectorAll('.btn-warning').forEach(item => {
            item.addEventListener('click', function() {
                const movieId = this.getAttribute('data-id');
                fetch('/Movie_Project/Movie/update/' + movieId)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modal-content-body' + movieId).innerHTML = html;
                    })
                    .catch(error => {
                        alert('Error loading movie form.');
                        console.error('Error:', error);
                    });
            });
        });
    </script>
</body>
</html>