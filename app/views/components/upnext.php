<div class="up-next-box">
    <h5 class="text-primary mb-3">Up next</h5>
    <?php 
    $upNextCount = 0;
    foreach ($movies as $index => $movie): 
        if ($index === 0) continue; 
        if ($upNextCount >= 3) break;
        $upNextCount++;
    ?>
        <div class="d-flex mb-3 align-items-center pt-3">
            <div class="me-2 position-relative">
                <img src="<?php echo $movie['poster'] ?? 'https://via.placeholder.com/60x90'; ?>" class="rounded" width="60" height="90" alt="<?php echo $movie['title']; ?>">
                <div class="play-icon-overlay position-absolute top-50 start-50 translate-middle">
                    <i class="fas fa-play-circle text-white"></i>
                </div>
            </div>
            <div>
                <h6 class="mb-0 text-white"><?php echo $movie['title']; ?></h6>
                <small class="text-muted">
                    <?php echo $movie['director'] ?? 'Unknown'; ?> • <?php echo $movie['release_year'] ?? '2025'; ?>
                </small>
                <div class="text-muted">
                    <i class="fas fa-thumbs-up me-1"></i> 0
                    <i class="fas fa-heart ms-2"></i> 0
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <a href="#" class="text-primary">Xem thêm <i class="fas fa-chevron-right"></i></a>
</div>
