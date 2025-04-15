<?php
// manage_actor.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Actors</title>
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
            <h4>Actor Manager</h4>
            <a id="add-actor" class="btn btn-primary"><i class="fas fa-plus"></i> Add Actor</a>
        </div>

        <!-- Actors Table -->
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
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editActorModal<?php echo $actor['id']; ?>" data-id="<?php echo $actor['id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                            <a href="/Movie_Project/Actor/delete/<?php echo $actor['id']; ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
                <div class="modal-body" id="modal-content-body"></div>
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
                    <div class="modal-body" id="modal-content-body<?php echo $actor['id']; ?>"></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add Actor Modal
        document.getElementById('add-actor').addEventListener('click', function() {
            fetch('/Movie_Project/Actor/add')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modal-content-body').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('addActorModal')).show();
                })
                .catch(error => console.error('Error loading add actor content:', error));
        });

        // Edit Actor Modal
        document.querySelectorAll('.btn-warning').forEach(item => {
            item.addEventListener('click', function() {
                const actorId = this.getAttribute('data-id');
                fetch('/Movie_Project/Actor/update/' + actorId)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modal-content-body' + actorId).innerHTML = html;
                    })
                    .catch(error => {
                        alert('Error loading actor form.');
                        console.error('Error:', error);
                    });
            });
        });
    </script>
</body>
</html>