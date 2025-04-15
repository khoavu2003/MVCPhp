<?php
// app/views/Movie/search.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Movie Project</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/Movie_Project/public/css/navbar/style.css" rel="stylesheet">
    <style>
        .movie-card {
            transition: transform 0.2s;
        }
        .movie-card:hover {
            transform: scale(1.05);
        }
        .movie-poster {
            height: 300px;
            object-fit: cover;
        }
        .watchlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'app/views/Utils/Navbar.php'; ?>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">

        <!-- Search Results -->
        <h2 class="mb-4">Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
        <?php if (empty($movies)): ?>
            <div class="alert alert-info" role="alert">
                No movies found matching your search.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($movies as $movie): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card movie-card position-relative">
                            <?php if ($showWatchlist): ?>
                                <?php
                                $isInWatchlist = false;
                                foreach ($watchlists as $watchlist) {
                                    if ($watchlist['movieId'] == $movie['id']) {
                                        $isInWatchlist = true;
                                        break;
                                    }
                                }
                                ?>
                                <button class="btn btn-sm watchlist-btn <?php echo $isInWatchlist ? 'btn-danger' : 'btn-outline-secondary'; ?>" 
                                        onclick="toggleWatchlist(<?php echo $movie['id']; ?>, this)">
                                    <i class="fas fa-heart"></i>
                                </button>
                            <?php endif; ?>
                            <a href="/Movie_Project/Movie/detail/<?php echo $movie['id']; ?>">
                                <img src="<?php echo htmlspecialchars($movie['poster'] ?: '/Movie_Project/public/images/default-poster.jpg'); ?>" 
                                     class="card-img-top movie-poster" 
                                     alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo $movie['genre_names'] ? htmlspecialchars($movie['genre_names']) : 'No genres'; ?>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted"><?php echo $movie['releaseYear']; ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/Movie_Project/Movie/search?query=<?php echo urlencode($query); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="/Movie_Project/Movie/search?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/Movie_Project/Movie/search?query=<?php echo urlencode($query); ?>&page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function toggleWatchlist(movieId, button) {
            fetch('/Movie_Project/Watchlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ movieId: movieId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('btn-danger');
                    button.classList.toggle('btn-outline-secondary');
                } else {
                    alert(data.message || 'Error toggling watchlist');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error toggling watchlist');
            });
        }
    </script>
</body>
</html>