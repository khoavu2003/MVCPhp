<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php include 'app/views/Utils/Sidebar.php'; ?>

    <div class="main-content">
        <div class="header">
            <h4>Actor Manager</h4>
        </div>
        <a href="#" id="add-movie" class="btn btn-primary">Add Actor</a>

        <!-- Table to display actors -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Bio</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actors as $actor): ?>
                    <tr>
                        <td><?php echo $actor['id']; ?></td>
                        <td><?php echo $actor['name']; ?></td>
                        <td><?php echo $actor['description']; ?></td>
                        <td>
                            <!-- Nút Edit Actor -->
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editActorModal<?php echo $actor['id']; ?>" id="openEditActorModal" data-id="<?php echo $actor['id']; ?>">Edit</button>
                            <a href="/Movie_Project/Actor/delete/<?php echo $actor['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <!-- Modal Add Actor -->
    <div class="modal fade" id="addActorModal" tabindex="-1" aria-labelledby="addActorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addActorModalLabel">Add New Actor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-content-body">
                    <!-- Content will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Actor -->
    <?php foreach ($actors as $actor): ?>
        <div class="modal fade" id="editActorModal<?php echo $actor['id']; ?>" tabindex="-1" aria-labelledby="editActorModalLabel<?php echo $actor['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editActorModalLabel<?php echo $actor['id']; ?>">Edit Actor: <?php echo $actor['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-content-body<?php echo $actor['id']; ?>">
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
        // When Add Actor button is clicked
        document.getElementById('add-movie').addEventListener('click', function () {
            // Fetch the Add Actor form dynamically
            fetch('/Movie_Project/Actor/add') // Adjust with the correct URL path
                .then(response => response.text())
                .then(html => {
                    // Load the form content into the modal-body
                    document.getElementById('modal-content-body').innerHTML = html;
                    // Show the modal if it's not already shown
                    var myModal = new bootstrap.Modal(document.getElementById('addActorModal'));
                    myModal.show();
                })
                .catch(error => {
                    console.log('Error loading add actor content:', error);
                });
        });

        // When Edit Actor button is clicked
        document.querySelectorAll('[id^="openEditActorModal"]').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default action

                var actorId = this.getAttribute('data-id'); // Get actor ID from data-id attribute
                // Fetch the Edit Actor form dynamically
                fetch('/Movie_Project/Actor/update/' + actorId) // Adjust with correct URL path
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modal-content-body' + actorId).innerHTML = html; // Load the form into the modal
                    })
                    .catch(error => {
                        alert('Error loading actor form.');
                        console.error('Error loading actor form:', error);
                    });
            });
        });
    </script>
</body>
</html>
