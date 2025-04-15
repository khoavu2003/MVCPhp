<?php
// Nếu $recentMovies không được định nghĩa, sử dụng mảng rỗng
$recentMovies = isset($recentMovies) ? $recentMovies : [];
?>

<section class="featured-today">
    <div class="section-title">
        <h2>Featured Today</h2>
        <a href="#" class="see-all">See all</a>
    </div>
    <div class="featured-grid">
        <?php 
        $featuredCount = 0;
        if (!empty($recentMovies)): 
            foreach ($recentMovies as $movie):
                if ($featuredCount < 4):
                    $featuredCount++;
        ?>
        <div class="featured-card">
            <img src="<?php echo isset($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/100x150'; ?>" 
                 alt="<?php echo isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Untitled'; ?>" 
                 class="featured-image">
            <div class="featured-content">
                <h3 class="featured-title"><?php echo isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Untitled'; ?></h3>
                <div class="featured-info">
                    <?php echo isset($movie['releaseYear']) ? htmlspecialchars($movie['releaseYear']) : 'Unknown'; ?> • 
                    <?php echo isset($movie['genre_names']) ? htmlspecialchars($movie['genre_names']) : 'Drama'; ?>
                </div>
                <div class="featured-footer">
                    <div class="featured-rating">
                        <i class="fas fa-star"></i>
                        <span><?php echo isset($movie['rating']) ? number_format($movie['rating'], 1) : '0.0'; ?></span>
                    </div>
                    <button class="featured-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/Movie/detail/<?php echo isset($movie['id']) ? htmlspecialchars($movie['id']) : '#'; ?>'">
                        <i class="fas fa-info-circle"></i> Details
                    </button>
                </div>
            </div>
        </div>
        <?php 
                endif;
            endforeach; 
        else:
        ?>
        <p>No movies found.</p>
        <?php endif; ?>
    </div>
</section>