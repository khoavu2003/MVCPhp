<?php
// index.php
session_start();

// Include Database và Movie
require_once 'app/config/database.php';
require_once 'app/models/Movie.php';

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Kết nối database
$db = new Database();
$conn = $db->getConnection();

// Khởi tạo object Movie
$movie = new Movie($conn);

// Lấy tổng số phim
$totalMovies = $movie->getTotalMovies();
$totalPages = ceil($totalMovies / $limit);

// Lấy danh sách phim
$stmt = $movie->getMoviesWithDetails($limit, $offset);
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Phim nổi bật (featured movie)
$featuredMovie = !empty($movies) ? $movies[0] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieDB - Your Movie Database</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link href="/Movie_Project/public/css/home/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/utils/navbar.php'; ?>

    <main class="main-content">
        <div class="container">
            <?php include 'app/views/components/banner.php'; ?>
            <?php include 'app/views/components/featured_today.php'; ?>
            <?php require_once 'app/views/components/movie_slider.php'; ?>
            <?php renderMovieSlider("Trending", $movies); ?>
            <?php renderMovieSlider("Top Rated", $movies, true); ?>
            <?php include 'app/views/components/pagination.php'; ?>
        </div>
    </main>

    <script>
        function addToWatchlist(movieId) {
            alert('Movie added to watchlist: ' + movieId);
        }
    </script>
</body>
</html>