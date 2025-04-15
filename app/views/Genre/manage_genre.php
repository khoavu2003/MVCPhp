<?php
// manage_genre.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Genres</title>
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
            <h4>Genre Manager</h4>
            <a id="add-genre" class="btn btn-primary"><i class="fas fa-plus"></i> Add Genre</a>
        </div>

        <!-- Genres Table -->
        <table class="table table-bordered genre-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($genres as $genre): ?>
                    <tr>
                        <td><?php echo $genre['name']; ?></td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editGenreModal<?php echo $genre['id']; ?>" data-id="<?php echo $genre['id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                            <a href="/Movie_Project/Genre/delete/<?php echo $genre['id']; ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Add Genre -->
    <div class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGenreModalLabel">Add New Genre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-content-body"></div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Genre -->
    <?php foreach ($genres as $genre): ?>
        <div class="modal fade" id="editGenreModal<?php echo $genre['id']; ?>" tabindex="-1" aria-labelledby="editGenreModalLabel<?php echo $genre['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGenreModalLabel<?php echo $genre['id']; ?>">Edit Genre: <?php echo $genre['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-content-body<?php echo $genre['id']; ?>"></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add Genre Modal
        document.getElementById('add-genre').addEventListener('click', function() {
            fetch('/Movie_Project/Genre/add')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modal-content-body').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('addGenreModal')).show();
                })
                .catch(error => console.error('Error loading add genre content:', error));
        });

        // Edit Genre Modal
        document.querySelectorAll('.btn-warning').forEach(item => {
            item.addEventListener('click', function() {
                const genreId = this.getAttribute('data-id');
                fetch('/Movie_Project/Genre/update/' + genreId)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modal-content-body' + genreId).innerHTML = html;
                    })
                    .catch(error => {
                        alert('Error loading genre form.');
                        console.error('Error:', error);
                    });
            });
        });
    </script>
</body>
</html>