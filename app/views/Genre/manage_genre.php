<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Genres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'app/views/Utils/Sidebar.php'; ?>
    <div class="main-content">
        <div class="header">
            <h4>Genre Manage</h4>
        </div>
        <h2>Manage Genres</h2>
        <a href="#" id="add-genre" class="btn btn-primary mb-3">Add New Genre</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($genres as $genre): ?>
                    <tr>
                        <td><?php echo $genre['id']; ?></td>
                        <td><?php echo $genre['name']; ?></td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editGenreModal<?php echo $genre['id']; ?>" id="openEditGenreModal" data-id="<?php echo $genre['id']; ?>">Edit</button>
                            <a href="/Movie_Project/Genre/delete/<?php echo $genre['id']; ?>" class="btn btn-danger">Delete</a>
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
                <div class="modal-body" id="modal-content-body">
                    <!-- Content will be dynamically loaded here -->
                </div>
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
                    <div class="modal-body" id="modal-content-body<?php echo $genre['id']; ?>">
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
        // When Add Genre button is clicked
        document.getElementById('add-genre').addEventListener('click', function() {
            // Fetch the Add Genre form dynamically
            fetch('/Movie_Project/Genre/add') // Adjust with the correct URL path
                .then(response => response.text())
                .then(html => {
                    // Load the form content into the modal-body
                    document.getElementById('modal-content-body').innerHTML = html;
                    // Show the modal if it's not already shown
                    var myModal = new bootstrap.Modal(document.getElementById('addGenreModal'));
                    myModal.show();
                })
                .catch(error => {
                    console.log('Error loading add genre content:', error);
                });
        });

        // When Edit Genre button is clicked
        document.querySelectorAll('[id^="openEditGenreModal"]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default action

                var genreId = this.getAttribute('data-id'); // Get genre ID from data-id attribute
                // Fetch the Edit Genre form dynamically
                fetch('/Movie_Project/Genre/update/' + genreId) // Adjust with correct URL path
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modal-content-body' + genreId).innerHTML = html; // Load the form into the modal
                    })
                    .catch(error => {
                        alert('Error loading genre form.');
                        console.error('Error loading genre form:', error);
                    });
            });
        });
    </script>
</body>

</html>