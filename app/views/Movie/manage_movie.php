<!-- manage_movie.php -->

<h2>Manage Movies</h2>

<!-- Nút Add Movie -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMovieModal">Add Movie</button>

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
                <!-- Nút Edit Movie, mở modal -->
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMovieModal<?php echo $movie['id']; ?>">Edit</button>
                <a href="/Movie_Project/Movie/delete/<?php echo $movie['id']; ?>" class="btn btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<!-- Modal Add Movie -->
<div class="modal fade" id="addMovieModal" tabindex="-1" aria-labelledby="addMovieModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMovieModalLabel">Add New Movie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Include the add movie form -->
        <?php include 'app/views/Movie/add.php'; ?>
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
            <div class="modal-body">
                <!-- Import the edit form -->
                <?php include 'app/views/Movie/edit.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
// Kích hoạt lại dropdown sau khi modal được mở
$('.modal').on('shown.bs.modal', function () {
    $('#actors').selectpicker('refresh');
    $('#genres').selectpicker('refresh');
});
</script>