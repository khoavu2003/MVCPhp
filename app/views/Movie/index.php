<?php
session_start();

require_once 'app/config/database.php';
require_once 'app/models/Movie.php';
require_once 'app/models/Actor.php';

// Define BASE_URL if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}

// Check if this is a homepage request
$url = $_GET['url'] ?? '';
if (empty($url) || $url === 'index') {
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();

    // Initialize Movie object
    $movie = new Movie($conn);

    // Get total movies and pages
    $totalMovies = $movie->getTotalMovies();
    $totalPages = ceil($totalMovies / $limit);

    // Get movie list
    $stmt = $movie->getMoviesWithDetails($limit, $offset);
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Featured movie
    $featuredMovie = !empty($movies) ? $movies[0] : null;

    // Initialize Actor object and get all actors
    $actor = new Actor($conn);
    $stmt = $actor->getAll();
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include the movie slider component
    require_once 'app/views/components/movie_slider.php';
    // Include the actor slider component
    require_once 'app/views/components/actor_slider.php';

    // Render the homepage
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

        <main class="main-content bg-dark">
            <div class="container">
                <?php include 'app/views/components/banner.php'; ?>
                <?php include 'app/views/components/featured_today.php'; ?>
                <?php renderMovieSlider("Trending", $movies); ?>
                <?php renderMovieSlider("Top Rated", $movies, true); ?>
                <?php renderActorSlider("Popular Actors", $actors); ?>
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
    <?php
} else {
    // Let the Router handle other requests
    App\Core\Router::handleRequest();
}