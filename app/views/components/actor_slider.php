<?php
function renderActorSlider($title, $actors) {
    ?>
    <section class="movie-slider">
        <div class="slider-container">
            <?php 
            if (!empty($actors)):
                foreach ($actors as $actor): 
                    $actorId = isset($actor['id']) ? htmlspecialchars($actor['id']) : '';
            ?>
            <div class="movie-card">
                <img src="<?php echo !empty($actor['profileImage']) ? htmlspecialchars($actor['profileImage']) : 'https://via.placeholder.com/185x278'; ?>" 
                     alt="<?php echo htmlspecialchars($actor['name']); ?>" 
                     class="movie-poster" 
                     onclick="window.location.href='<?php echo BASE_URL; ?>/actor/detail/<?php echo $actorId; ?>'">
                <div class="card-content">
                    <h3 class="card-title"><?php echo htmlspecialchars($actor['name']); ?></h3>
                    <div class="card-info">
                        <?php echo htmlspecialchars($actor['birthDate'] ? date('Y', strtotime($actor['birthDate'])) : 'Unknown'); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>
                <p>No actors found.</p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}
?>