<?php
// If $newMovies is not defined, fallback to empty array
$newMovies = isset($newMovies) ? $newMovies : [];
?>
<section class="banner d-flex">
    <!-- Left: Carousel -->
    <div class="carousel-container w-75">
        <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
            <?php 
                $bannerCount = 0;
                if (!empty($newMovies)):
                    foreach ($newMovies as $index => $movie):
                        if ($bannerCount < 6):
                            $bannerCount++;
                            $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo !empty($movie['bannerImage']) ? htmlspecialchars($movie['bannerImage']) : 'https://via.placeholder.com/1200x450'; ?>" 
                         class="d-block w-100 banner-image" 
                         alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    <div class="banner-thumbnail">
                        <img src="<?php echo !empty($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/100x150'; ?>" 
                             class="banner-thumbnail-img" 
                             alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <div class="thumbnail-content">
                            <h3 class="thumbnail-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                            <button class="play-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>'">
                                <i class="fas fa-play"></i> Play
                            </button>
                        </div>
                    </div>
                </div>
                <?php 
                        endif;
                    endforeach;
                else:
            ?>
                <div class="carousel-item active">
                    <img src="https://via.placeholder.com/1200x450" class="d-block w-100 banner-image" alt="No movies">
                    <div class="banner-thumbnail">
                        <div class="thumbnail-content">
                            <h3 class="thumbnail-title">No new movies available</h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Right: Up Next -->
    <div class="up-next-container w-25 ps-3">
        <?php include 'app/views/components/upnext.php'; ?>
    </div>
</section>