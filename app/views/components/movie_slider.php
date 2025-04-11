<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}

function renderMovieSlider($title, $movies, $sortByRating = false) {
    ?>
    <section class="movie-slider">
        <div class="section-title">
            <h2><?php echo htmlspecialchars($title); ?></h2>
            <a href="#" class="see-all">See all</a>
        </div>
        <div class="slider-container">
            <?php 
            if (!empty($movies)):
                if ($sortByRating) {
                    usort($movies, function($a, $b) {
                        $ratingA = isset($a['rating']) ? floatval($a['rating']) : 0;
                        $ratingB = isset($b['rating']) ? floatval($b['rating']) : 0;
                        return $ratingB <=> $ratingA;
                    });
                }
                foreach ($movies as $movie): 
                    $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
            ?>
            <div class="movie-card">
                <img src="<?php echo !empty($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/185x278'; ?>" 
                     alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                     class="movie-poster" 
                     onclick="window.location.href='<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>'">
                <div class="card-actions">
                    <button class="add-watchlist" onclick="addToWatchlist('<?php echo $movieId; ?>')">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-content">
                    <div class="card-rating">
                        <i class="fas fa-star"></i>
                        <span><?php echo isset($movie['rating']) ? htmlspecialchars($movie['rating']) : '8.0'; ?></span>
                    </div>
                    <h3 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                    <div class="card-info"><?php echo htmlspecialchars($movie['releaseYear']); ?></div>
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