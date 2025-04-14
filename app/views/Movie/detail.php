<?php
session_start();
define('BASE_URL', '/Movie_Project');

// Hàm chuyển URL YouTube thành dạng embed
function getYouTubeEmbedUrl($url)
{
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Sử dụng Font Awesome phiên bản mới hơn để đảm bảo hiển thị -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/detail/style.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'app/views/Utils/Navbar.php'; ?>

    <!-- Nội dung chi tiết phim -->
    <div class="trailer-page">
        <div class="container">
            <div class="main-content">
                <!-- Trailer Section -->
                <div class="trailer-section">
                    <a href="/Movie_Project/" class="back-button">Quay lại</a>
                    <?php if (!empty($movie['trailer'])): ?>
                        <iframe
                            src="<?php echo htmlspecialchars(getYouTubeEmbedUrl($movie['trailer'])) . '?autoplay=1&mute=1'; ?>"
                            title="<?php echo htmlspecialchars($movie['title']); ?>"
                            allow="autoplay; encrypted-media"
                            allowfullscreen>
                        </iframe>
                    <?php else: ?>
                        <p class="text-white text-lg">Hiện tại chưa có trailer cho phim này.</p>
                    <?php endif; ?>
                </div>

                <!-- Movie Info Section -->
                <div class="movie-info">
                    <div class="movie-header">
                        <img src="<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                    </div>
                    <hr>
                    <p><?php echo htmlspecialchars($movie['description']); ?></p>
                    <p><strong>Thể loại:</strong> <?php echo !empty($genres) ? implode(', ', array_map('htmlspecialchars', array_column($genres, 'name'))) : 'Không có thể loại'; ?></p>
                    <p><strong>Đạo diễn:</strong> <?php echo htmlspecialchars($movie['director'] ?: 'Không rõ'); ?></p>
                    <p><strong>Diễn viên:</strong> <?php echo !empty($actors) ? implode(', ', array_map('htmlspecialchars', array_column($actors, 'name'))) : 'Không có diễn viên'; ?></p>
                    <p><strong>Năm phát hành:</strong> <?php echo htmlspecialchars($movie['releaseYear']); ?></p>
                    <div class="user-rating">
                        <div class="stars" data-movie-id="<?= $movie['id'] ?>">
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <i class="star <?= $userRating >= $i ? 'active' : '' ?>" data-rating="<?= $i ?>">★</i>
                            <?php endfor; ?>
                        </div>
                        <div class="rating-message"></div>
                    </div>

                    <div class="rating">
                        <span class="average"><?= $averageRating ?></span>
                        <span class="count">(<?= $ratingCount ?> lượt)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Videos -->
        <?php include 'app/views/components/featured_videos.php'; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- JavaScript cho chức năng đánh giá -->
    <script>
        (function() {
            console.log('Rating script started');

            document.addEventListener('DOMContentLoaded', () => {
                console.log('DOM fully loaded for rating');

                const starsContainer = document.querySelector('.user-rating .stars');
                const stars = document.querySelectorAll('.user-rating .stars i');
                const ratingMessage = document.querySelector('.rating-message');
                const movieId = starsContainer ? starsContainer.dataset.movieId : null;
                const averageRatingElement = document.querySelector('.rating .average');
                const ratingCountElement = document.querySelector('.rating .count');

                // Lấy điểm đánh giá từ server truyền vào
                const userRating = <?php echo $userRating ?? 0; ?>;
                let currentUserRating = userRating;

                if (!starsContainer || !movieId) {
                    console.error('Stars container or movieId not found');
                    return;
                }

                if (stars.length === 0) {
                    console.error('No stars found in .user-rating .stars');
                    return;
                }

                console.log('Stars found:', stars.length);

                stars.forEach((star, index) => {
                    star.style.cursor = 'pointer';
                    star.style.pointerEvents = 'auto';

                    // Khi click vào sao
                    star.addEventListener('click', () => {
                        const rating = parseInt(star.dataset.rating);
                        console.log('Star clicked:', rating);

                        fetch('<?php echo BASE_URL; ?>/Movie/rate/' + movieId, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    rating: rating
                                })
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                return response.json();
                            })
                            .then(data => {
                                console.log('Response data:', data);
                                if (data.success) {
                                    const newUserRating = parseInt(data.userRating);
                                    currentUserRating = newUserRating;

                                    // Cập nhật lại giao diện sao
                                    stars.forEach(s => {
                                        s.classList.remove('active');
                                        if (parseInt(s.dataset.rating) <= newUserRating) {
                                            s.classList.add('active');
                                        }
                                    });

                                    // Cập nhật trung bình và số lượt
                                    averageRatingElement.textContent = data.averageRating;
                                    ratingCountElement.textContent = ` (${data.ratingCount} lượt)`;

                                    // Hiển thị thông báo
                                    ratingMessage.textContent = data.message;
                                    setTimeout(() => ratingMessage.textContent = '', 3000);
                                } else {
                                    ratingMessage.textContent = data.message;
                                    setTimeout(() => ratingMessage.textContent = '', 3000);
                                }
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                                ratingMessage.textContent = 'Có lỗi xảy ra khi gửi đánh giá.';
                                setTimeout(() => ratingMessage.textContent = '', 3000);
                            });
                    });

                    // Hover hiệu ứng
                    star.addEventListener('mouseover', () => {
                        const hoverRating = parseInt(star.dataset.rating);
                        stars.forEach(s => {
                            s.classList.remove('active');
                            if (parseInt(s.dataset.rating) <= hoverRating) {
                                s.classList.add('active');
                            }
                        });
                    });

                    // Khi rời chuột ra khỏi sao
                    star.addEventListener('mouseout', () => {
                        stars.forEach(s => {
                            s.classList.remove('active');
                            if (parseInt(s.dataset.rating) <= currentUserRating) {
                                s.classList.add('active');
                            }
                        });
                    });
                });

                // Hiển thị sao đúng theo rating ban đầu
                stars.forEach(s => {
                    if (parseInt(s.dataset.rating) <= currentUserRating) {
                        s.classList.add('active');
                    }
                });
            }, {
                once: true
            });
        })();
    </script>



</body>

</html>