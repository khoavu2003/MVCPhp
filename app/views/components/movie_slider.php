<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}

function renderMovieSlider($title, $movies, $sortByRating = false) {
    $movies = array_slice($movies, 0, 6);
    ?>
    <div class="section-title">
        <h2 class="titleh2" style="color: white;"><?php echo htmlspecialchars($title); ?></h2>
        <a href="<?php echo BASE_URL; ?>/movie/<?php echo $sortByRating ? 'topRated' : 'newMovies'; ?>" class="see-all">See all</a>
    </div>
    <section class="movie-slider">
        <div class="slider-container">
            <?php 
            if (!empty($movies)):
                if ($sortByRating) {
                    usort($movies, function($a, $b) {
                        $ratingA = isset($a['rating']) && $a['rating'] !== '' && $a['rating'] !== null ? floatval($a['rating']) : 0;
                        $ratingB = isset($b['rating']) && $b['rating'] !== '' && $b['rating'] !== null ? floatval($b['rating']) : 0;
                        return $ratingB <=> $ratingA;
                    });
                }
                foreach ($movies as $movie): 
                    $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
                    $movieTitle = isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Untitled';
                    $poster = isset($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/185x278';
                    $rating = isset($movie['rating']) && $movie['rating'] !== '' && $movie['rating'] !== null ? number_format(floatval($movie['rating']), 1) : '0.0';
                    $releaseYear = isset($movie['releaseYear']) ? htmlspecialchars($movie['releaseYear']) : 'Unknown';
            ?>
            <div class="movie-card">
                <img src="<?php echo $poster; ?>" 
                     alt="<?php echo $movieTitle; ?>" 
                     class="movie-poster" 
                     onclick="window.location.href='<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>'">
                <div class="card-actions">
                    <button class="add-watchlist" onclick="addToWatchlist('<?php echo $movieId; ?>')">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-content">
                    <div class="card-rating">
                        <i class="fas fa-star" style="color: #f5c518;"></i>
                        <span><?php echo $rating; ?></span>
                    </div>
                    <h3 class="card-title" style="color: white;"><?php echo $movieTitle; ?></h3>
                    <div class="card-info"><?php echo $releaseYear; ?></div>
                </div>
            </div>
            <?php endforeach; else: ?>
                <p>No movies found.</p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}
?>