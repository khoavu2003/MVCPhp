<section class="banner d-flex">
    <!-- Left: Carousel -->
    <div class="carousel-container w-75">
        <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
            <?php 
                $bannerCount = 0;
                if (!empty($movies)):
                    foreach ($movies as $index => $movie):
                        if ($bannerCount < 6):
                            $bannerCount++;
                            $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo !empty($movie['bannerImage']) ? $movie['bannerImage'] : 'https://via.placeholder.com/1200x450'; ?>" class="d-block w-100 banner-image" alt="<?php echo $movie['title']; ?>">
                    <div class="banner-thumbnail">
                        <img src="<?php echo !empty($movie['poster']) ? $movie['poster'] : 'https://via.placeholder.com/100x150'; ?>" 
                            class="banner-thumbnail-img" 
                            alt="<?php echo $movie['title']; ?>">
                        <div class="thumbnail-content">
                            <h3 class="thumbnail-title"><?php echo $movie['title']; ?></h3>
                            <button class="play-btn" onclick="window.location.href='/Movie_Project/Movie/detail/<?php echo $movieId; ?>'">
                                <i class="fas fa-play"></i> Play
                            </button>
                        </div>
                    </div>
                </div>
                <?php 
                        endif;
                    endforeach;
                endif;
                ?>

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
