<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}

function renderMovieSlider($title, $movies, $sortByRating = false) {
    ?>
    <section class="movie-slider">
        <div class="slider-container">
            <?php 
            if (!empty($movies)):
                if ($sortByRating) {
                    usort($movies, function($a, $b) {
                        $ratingA = isset($a['rating']) && $a['rating'] !== '' ? floatval($a['rating']) : 0;
                        $ratingB = isset($b['rating']) && $b['rating'] !== '' ? floatval($b['rating']) : 0;
                        return $ratingB <=> $ratingA;
                    });
                }
                foreach ($movies as $movie): 
                    $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
                    $movieTitle = isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Untitled';
                    $poster = isset($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/185x278';
                    $rating = isset($movie['rating']) && $movie['rating'] !== '' ? htmlspecialchars($movie['rating']) : '0';
                    $releaseYear = isset($movie['releaseYear']) ? htmlspecialchars($movie['releaseYear']) : 'Unknown';
            ?>
            <div class="movie-card">
                <img src="<?php echo $poster; ?>" 
                     alt="<?php echo $movieTitle; ?>" 
                     class="movie-poster" 
                     onclick="window.location.href='<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>'">
                <div class="card-actions">
                    <button class="add-watchlist" onclick="addToWatchlistSlider('<?php echo $movieId; ?>')">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-content">
                    <div class="card-rating">
                        <i class="fas fa-star" style="color: #f5c518;"></i>
                        <span><?php echo $rating; ?></span>
                    </div>
                    <h3 class="card-title" style="color: white;"><?php echo $movieTitle; ?></h3>
                    <div class="card-info"><?php echo $releaseYear; ?></div>
                </div>
            </div>
            <?php endforeach; else: ?>
                <p>No movies found.</p>
            <?php endif; ?>
        </div>
        <!-- Container để gắn Shadow DOM -->
        <div id="watchlistSliderContainer"></div>
    </section>

    <!-- JavaScript để xử lý chức năng Add to Watchlist -->
    <script>
        // Kiểm tra để tránh gắn nhiều lần nếu có nhiều slider trên cùng một trang
        if (document.getElementById('watchlistSliderContainer') && !document.getElementById('watchlistSliderContainer').hasAttribute('data-initialized')) {
            document.getElementById('watchlistSliderContainer').setAttribute('data-initialized', 'true');

            // Tạo Shadow DOM
            const shadow = document.getElementById('watchlistSliderContainer').attachShadow({ mode: 'open' });

            // Tạo nội dung modal
            const modalHtml = `
                <style>
                    /* CSS cô lập trong Shadow DOM */
                    .watchlist-overlay {
                        display: none;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.9);
                        z-index: 1000;
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
            let currentMovieId = null;

            // Hàm addToWatchlistSlider dành riêng cho slider
            window.addToWatchlistSlider = function(movieId) {
                if (!movieId) {
                    console.error('Movie ID is undefined or empty');
                    return;
                }
                currentMovieId = movieId;

                // Mở modal khi nhấn nút "Add to Watchlist"
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
            };

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

            // Xử lý khi nhấn nút "Add to Watchlist" trong modal
            submitWatchlistBtn.addEventListener('click', () => {
                const selectedWatchlist = shadow.querySelector('input[name="watchlist"]:checked');
                let watchlistId = selectedWatchlist ? selectedWatchlist.value : null;

                console.log('Selected watchlistId:', watchlistId);

                fetch('<?php echo BASE_URL; ?>/Watchlist/addToWatchlist/' + currentMovieId + (watchlistId ? '/' + watchlistId : ''), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ createDefault: !selectedWatchlist })
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
        }
    </script>
    <?php
}
?>