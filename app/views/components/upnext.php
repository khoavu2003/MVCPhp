<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}
?>

<div class="up-next-box">
    <h5 class="text-light mb-3">Up next</h5>
    <?php 
    $upNextCount = 0;
    foreach ($movies as $index => $movie): 
        if ($index === 0) continue; 
        if ($upNextCount >= 3) break;
        $upNextCount++;
        $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
        $movieTitle = isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Untitled';
        $poster = isset($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/60x90';
        $director = isset($movie['director']) ? htmlspecialchars($movie['director']) : 'Unknown';
        $releaseYear = isset($movie['release_year']) ? htmlspecialchars($movie['release_year']) : '2025';
    ?>
        <div class="d-flex mb-3 align-items-center pt-3">
            <div class="me-2 position-relative">
                <a href="<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>">
                    <img src="<?php echo $poster; ?>" 
                         class="rounded" 
                         width="60" 
                         height="90" 
                         alt="<?php echo $movieTitle; ?>">
                    <div class="play-icon-overlay position-absolute top-50 start-50 translate-middle">
                        <i class="fas fa-play-circle text-light"></i>
                    </div>
                </a>
            </div>
            <div>
                <h6 class="mb-0 text-light">
                    <a href="<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>" 
                       class="text-light text-decoration-none">
                        <?php echo $movieTitle; ?>
                    </a>
                </h6>
                <small class="text-secondary">
                    <?php echo $director; ?> • <?php echo $releaseYear; ?>
                </small>
                <div class="text-secondary">
                    <i class="fas fa-thumbs-up me-1"></i> 0
                    <i class="fas fa-heart ms-2"></i> 0
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <a href="#" class="text-info">Xem thêm <i class="fas fa-chevron-right"></i></a>
</div>