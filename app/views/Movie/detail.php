<?php
session_start();
define('BASE_URL', '/Movie_Project');

// Hàm chuyển URL YouTube thành dạng embed
function getYouTubeEmbedUrl($url) {
    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }

    $videoId = '';
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
        $videoId = $matches[1];
    }

    return $videoId ? "https://www.youtube.com/embed/{$videoId}" : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Chi tiết phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/detail/style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include 'app/views/Utils/Navbar.php'; ?>

    <!-- Nội dung chi tiết phim -->
    <div class="container">
        <!-- Trailer -->


        <div class="movie-info d-flex flex-wrap text-white p-4 rounded">
            <div class="trailer-video col-md-7">
                <?php if (!empty($movie['trailer'])): ?>
                    <div class="trailer-section mb-3">
                    <iframe class="w-100" height="400"
                        src="<?php echo htmlspecialchars(getYouTubeEmbedUrl($movie['trailer'])) . '?autoplay=1&mute=1'; ?>" 
                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

                    </div>
                <?php else: ?>
                    <p class="text-danger">Không có trailer.</p>
                <?php endif; ?>
            </div>

            <div class="movie-details col-md-5 px-4">
                <h2 class="fw-bold"><?php echo htmlspecialchars($movie['title']); ?></h2>
                <p><strong>Thể loại:</strong> <?php echo !empty($genres) ? implode(', ', array_map('htmlspecialchars', array_column($genres, 'name'))) : 'Chưa có thông tin'; ?></p>
                <p><strong>Đạo diễn:</strong> <?php echo htmlspecialchars($movie['director']); ?></p>
                <p><strong>Diễn viên:</strong> <?php echo !empty($actors) ? implode(', ', array_map('htmlspecialchars', array_column($actors, 'name'))) : 'Chưa có thông tin'; ?></p>
                <p><strong>Năm phát hành:</strong> <?php echo !empty($movie['releaseYear']) ? htmlspecialchars($movie['releaseYear']) : 'Unknown'; ?>
                <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>

                <div class="rating text-warning mb-3">
                    <i class="fas fa-star"></i> <strong>8.0</strong> / 10 <span class="text-light">(1 lượt)</span>
                </div>
            </div>
        </div>


        <!-- Featured Videos (giả lập, có thể lấy từ DB) -->
        <div class="featured-videos">
            <h3>Featured Videos</h3>
            <div class="video-carousel">
                <div class="video-item">
                    <img src="https://via.placeholder.com/300x169" alt="Video 1">
                    <p>Video 1</p>
                </div>
                <div class="video-item">
                    <img src="https://via.placeholder.com/300x169" alt="Video 2">
                    <p>Video 2</p>
                </div>
                <div class="video-item">
                    <img src="https://via.placeholder.com/300x169" alt="Video 3">
                    <p>Video 3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>