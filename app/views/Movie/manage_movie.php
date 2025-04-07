<h2>Manage Movies</h2>

<!-- Nút Add Movie -->
<a href="#" id="openAddMovieModal" data-bs-toggle="modal" data-bs-target="#addMovieModal" class="btn btn-primary">Add Movie</a>

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
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMovieModal<?php echo $movie['id']; ?>">Edit</button> 
                <a href="/Movie_Project/Movie/delete/<?php echo $movie['id']; ?>" class="btn btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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

<script>
    // Kích hoạt modal và lấy form từ AJAX
    $(document).ready(function() {
        // Khi nhấn vào nút mở modal Add Movie
        $('#openAddMovieModal').click(function(e) {
            e.preventDefault();  // Ngừng hành động mặc định của liên kết

            // Sử dụng AJAX để lấy nội dung của form thêm movie
            $.ajax({
                url: '/Movie_Project/Movie/add',  // Đường dẫn tới controller trả về form
                type: 'GET',
                success: function(response) {
                    // Đổ nội dung trả về vào modal-body
                    $('#modal-content-body').html(response);
                },
                error: function() {
                    alert('Error loading movie form.');
                }
            });
        });
    });
</script>
