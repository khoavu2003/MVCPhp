<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - <?php echo htmlspecialchars($watchlist['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/Movie_Project/public/css/watchlist/watchlist-movies.css" rel="stylesheet"> <!-- Include file CSS mới -->
</head>

<body>
<?php include 'app/views/Utils/Navbar.php'; ?>

<div class="watchlist-movies-page">
    <div class="container watchlist-container text-white">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🎬 <?php echo htmlspecialchars($watchlist['name']); ?></h1>
            <a href="/Movie_Project/Watchlist" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Watchlists
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <p class="text-muted mb-4"><em><?php echo htmlspecialchars($watchlist['description']); ?></em></p>

        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="row movie-item align-items-center">
                    <div class="col-md-2">
                        <img src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title']; ?>">
                    </div>
                    <div class="col-md-8">
                        <h5 class="text-white"><?php echo htmlspecialchars($movie['title']); ?></h5>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="/Movie_Project/Watchlist/removeFromWatchlist/<?php echo $watchlist['id']; ?>/<?php echo $movie['id']; ?>"
                           class="btn btn-outline-danger btn-sm">
                           <i class="bi bi-trash"></i> Remove
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No movies in this watchlist.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn thông báo sau 3 giây
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                }, 3000); // 3000ms = 3 giây
            });
        });
    </script>
</body>

</html>