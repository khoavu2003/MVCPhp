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
    <style>
        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .movie-header-content {
            display: flex;
            flex-direction: column;
            margin-left: 1.25rem;
        }

        .movie-header {
            display: flex;
            align-items: flex-start;
        }
    </style>
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
                        <div class="movie-header-content">
                            <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                            <button class="movie-watchlist-btn" data-movie-id="<?php echo $movie['id']; ?>" style="background-color: transparent; border: 2px solid #facc15; color: #facc15; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; display: flex; align-items: center; gap: 0.25rem; transition: all 0.3s ease;">
                                <i class="fas fa-plus" style="font-size: 0.75rem;"></i> Add to Watchlist
                            </button>
                            <div id="watchlistContainer"></div>
                        </div>
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

    <!-- JavaScript cho chức năng watchlist -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addToWatchlistBtn = document.querySelector('.movie-watchlist-btn');
            const watchlistContainer = document.getElementById('watchlistContainer');
            const movieId = addToWatchlistBtn ? addToWatchlistBtn.dataset.movieId : null;

            if (!addToWatchlistBtn || !movieId || !watchlistContainer) {
                console.error('Add to Watchlist button, movieId, or container not found');
                return;
            }

            // Tạo Shadow DOM
            const shadow = watchlistContainer.attachShadow({
                mode: 'open'
            });

            // Tạo nội dung modal
            const modalHtml = `
        <style>
            /* CSS cô lập trong Shadow DOM */
            .watchlist-overlay {
                display: none;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.9);
                z-index: 10;
                justify-content: center;
                align-items: center;
            }
            .watchlist-content {
                background: linear-gradient(145deg, #1a1a1a, #121212);
                padding: 1.5rem;
                border-radius: 12px;
                width: 90%;
                max-width: 350px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
                position: relative;
                animation: slideIn 0.3s ease;
            }
            @keyframes slideIn {
                from {
                    transform: translateY(-20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            .watchlist-content h3 {
                color: #fff;
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 1rem;
                text-align: center;
            }
            .watchlist-content .close-modal {
                position: absolute;
                top: 0.5rem;
                right: 0.5rem;
                font-size: 1.25rem;
                color: #9ca3af;
                cursor: pointer;
                transition: color 0.3s ease;
            }
            .watchlist-content .close-modal:hover {
                color: #facc15;
            }
            .watchlist-content .watchlist-items {
                max-height: 150px;
                overflow-y: auto;
                margin-bottom: 1rem;
                background-color: #1f1f1f;
                border-radius: 8px;
                padding: 0.5rem;
            }
            .watchlist-content .watchlist-items::-webkit-scrollbar {
                width: 6px;
            }
            .watchlist-content .watchlist-items::-webkit-scrollbar-thumb {
                background-color: #facc15;
                border-radius: 3px;
            }
            .watchlist-content .watchlist-items label {
                display: flex;
                align-items: center;
                color: #d1d5db;
                padding: 0.5rem;
                cursor: pointer;
                border-radius: 6px;
                transition: background-color 0.3s ease;
            }
            .watchlist-content .watchlist-items label:hover {
                background-color: #2d2d2d;
            }
            .watchlist-content .watchlist-items input[type="radio"] {
                margin-right: 0.5rem;
                accent-color: #facc15;
            }
            .watchlist-content .watchlist-submit {
                background-color: #facc15;
                color: #000;
                padding: 0.5rem 1rem;
                border-radius: 25px;
                border: none;
                font-size: 0.875rem;
                font-weight: 600;
                width: 100%;
                transition: all 0.3s ease;
            }
            .watchlist-content .watchlist-submit:hover {
                background-color: #eab308;
                transform: scale(1.02);
            }
            .watchlist-content .watchlist-message {
                margin-top: 1rem;
                text-align: center;
                font-size: 0.875rem;
                color: #34d399;
            }
        </style>
        <div class="watchlist-overlay" id="watchlistModal">
            <div class="watchlist-content">
                <span class="close-modal">×</span>
                <h3>Select a Watchlist</h3>
                <div class="watchlist-items" id="watchlistOptions"></div>
                <button class="watchlist-submit" id="submitWatchlistBtn">Add to Watchlist</button>
                <div class="watchlist-message" id="watchlistMessage"></div>
            </div>
        </div>
    `;

            // Thêm nội dung modal vào Shadow DOM
            shadow.innerHTML = modalHtml;

            // Lấy các phần tử trong Shadow DOM
            const watchlistModal = shadow.querySelector('#watchlistModal');
            const closeModal = shadow.querySelector('.close-modal');
            const watchlistOptions = shadow.querySelector('#watchlistOptions');
            const submitWatchlistBtn = shadow.querySelector('#submitWatchlistBtn');
            const watchlistMessage = shadow.querySelector('#watchlistMessage');

            // Mở modal khi nhấn nút "Add to Watchlist"
            addToWatchlistBtn.addEventListener('click', () => {
                fetch('<?php echo BASE_URL; ?>/Watchlist/getUserWatchlists', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            watchlistOptions.innerHTML = '';
                            if (data.watchlists.length === 0) {
                                watchlistOptions.innerHTML = '<p class="text-gray-400 text-sm">No watchlists found. A default watchlist will be created.</p>';
                            } else {
                                data.watchlists.forEach(watchlist => {
                                    const label = document.createElement('label');
                                    label.innerHTML = `
                            <input type="radio" name="watchlist" value="${watchlist.id}" ${watchlist.name === 'Default Watchlist' ? 'checked' : ''}>
                            ${watchlist.name}
                        `;
                                    watchlistOptions.appendChild(label);
                                });
                            }
                            watchlistModal.style.display = 'flex';
                        } else {
                            console.error('Failed to fetch watchlists:', data.message);
                            watchlistMessage.textContent = data.message || 'Failed to load watchlists.';
                            watchlistMessage.style.color = '#ef4444';
                            setTimeout(() => {
                                watchlistMessage.textContent = '';
                                watchlistMessage.style.color = '#34d399';
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching watchlists:', error);
                        watchlistMessage.textContent = 'An error occurred while loading watchlists.';
                        watchlistMessage.style.color = '#ef4444';
                        setTimeout(() => {
                            watchlistMessage.textContent = '';
                            watchlistMessage.style.color = '#34d399';
                        }, 2000);
                    });
            });

            // Đóng modal khi nhấn nút đóng
            closeModal.addEventListener('click', () => {
                watchlistModal.style.display = 'none';
                watchlistMessage.textContent = '';
            });

            // Đóng modal khi nhấn ra ngoài nội dung modal
            watchlistModal.addEventListener('click', (e) => {
                if (e.target === watchlistModal) {
                    watchlistModal.style.display = 'none';
                    watchlistMessage.textContent = '';
                }
            });

            // Xử lý khi nhấn nút "Add to Watchlist"
            submitWatchlistBtn.addEventListener('click', () => {
                const selectedWatchlist = shadow.querySelector('input[name="watchlist"]:checked');
                let watchlistId = selectedWatchlist ? selectedWatchlist.value : null;

                console.log('Selected watchlistId:', watchlistId);

                fetch('<?php echo BASE_URL; ?>/Watchlist/addToWatchlist/' + movieId + (watchlistId ? '/' + watchlistId : ''), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            createDefault: !selectedWatchlist
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            watchlistMessage.textContent = 'Added to watchlist successfully!';
                            const redirectWatchlistId = watchlistId || data.watchlistId;
                            console.log('Redirect watchlistId:', redirectWatchlistId);
                            if (!redirectWatchlistId) {
                                console.error('No watchlistId provided for redirect');
                                watchlistMessage.textContent = 'Added to watchlist, but failed to redirect.';
                                watchlistMessage.style.color = '#ef4444';
                                return;
                            }
                            setTimeout(() => {
                                watchlistModal.style.display = 'none';
                                watchlistMessage.textContent = '';
                                const redirectUrl = '<?php echo BASE_URL; ?>/Watchlist/movies/' + redirectWatchlistId;
                                console.log('Redirecting to:', redirectUrl);
                                window.location.href = redirectUrl;
                            }, 2000);
                        } else {
                            watchlistMessage.textContent = data.message || 'Failed to add to watchlist.';
                            watchlistMessage.style.color = '#ef4444';
                            setTimeout(() => {
                                watchlistMessage.textContent = '';
                                watchlistMessage.style.color = '#34d399';
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to watchlist:', error);
                        watchlistMessage.textContent = 'An error occurred while adding to watchlist.';
                        watchlistMessage.style.color = '#ef4444';
                        setTimeout(() => {
                            watchlistMessage.textContent = '';
                            watchlistMessage.style.color = '#34d399';
                        }, 2000);
                    });
            });
        });
    </script>


</body>

</html>