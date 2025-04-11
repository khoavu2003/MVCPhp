<?php
// Đảm bảo BASE_URL đã được định nghĩa
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}

// Hàm tính số trang và lấy phim cho trang hiện tại
function getFeaturedMoviesForPage($movies, $currentPage, $moviesPerPage) {
    $startIndex = $currentPage * $moviesPerPage;
    return array_slice($movies, $startIndex, $moviesPerPage);
}
?>

<div class="featured-videos" id="featured-videos">
    <h3>Featured Videos</h3>

    <?php
    // Sắp xếp phim theo đánh giá trung bình (giảm dần)
    usort($featuredMovies, function($a, $b) {
        if ($b['averageRating'] != $a['averageRating']) {
            return $b['averageRating'] - $a['averageRating'];
        }
        return strcmp($b['title'], $a['title']);
    });

    // Lấy 9 phim đầu tiên
    $topMovies = array_slice($featuredMovies, 0, 9);

    // Số phim mỗi trang
    $moviesPerPage = 3;
    $totalMovies = count($topMovies);
    $totalPages = ceil($totalMovies / $moviesPerPage);

    // Lấy trang hiện tại (mặc định là 0)
    $currentPage = 0; // Ban đầu hiển thị trang đầu tiên

    // Lấy phim cho trang hiện tại
    $moviesToShow = getFeaturedMoviesForPage($topMovies, $currentPage, $moviesPerPage);
    $startIndex = $currentPage * $moviesPerPage + 1;
    $endIndex = min($startIndex + count($moviesToShow) - 1, $totalMovies);
    ?>

    <!-- Hiển thị thông tin số lượng phim -->
    <div class="showing-info" id="showing-info">
        <?php if ($totalMovies > 0): ?>
            Showing <?php echo $startIndex; ?> - <?php echo $endIndex; ?> of <?php echo $totalMovies; ?>
        <?php else: ?>
            No featured movies
        <?php endif; ?>
    </div>

    <!-- Grid hiển thị video -->
    <div class="video-carousel" id="video-carousel">
        <!-- Nút mũi tên trái -->
        <div class="nav-button">
            <button class="nav-arrow prev-arrow">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <!-- Danh sách phim -->
        <div class="video-items" id="video-items">
            <?php if (!empty($moviesToShow)): ?>
                <?php foreach ($moviesToShow as $movie): ?>
                    <div class="video-item">
                        <div class="relative">
                            <a href="<?php echo BASE_URL; ?>/Movie/detail/<?php echo htmlspecialchars($movie['id']); ?>">
                                <img 
                                    src="<?php echo htmlspecialchars($movie['bannerImage']); ?>" 
                                    alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                                    class="w-full h-44 object-cover"
                                    onerror="this.src='https://picsum.photos/1200/500'; this.onerror=null;"
                                >
                            </a>
                            <div class="play-icon">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>
                        <div class="video-content">
                            <p><?php echo htmlspecialchars($movie['title']); ?></p>
                            <div class="rating-small">
                                <i class="fa-solid fa-star"></i> <?php echo number_format($movie['averageRating'], 1); ?> / 10
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-400">Không có phim nào để hiển thị.</p>
            <?php endif; ?>
        </div>

        <!-- Nút mũi tên phải -->
        <div class="nav-button">
            <button class="nav-arrow next-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Truyền dữ liệu phim vào JavaScript -->
    <script>
        (function() {
            console.log('Featured videos script started'); // Kiểm tra script có chạy không

            const allMovies = <?php echo json_encode($topMovies); ?>;
            let currentPage = <?php echo $currentPage; ?>;
            const moviesPerPage = <?php echo $moviesPerPage; ?>;
            const totalPages = <?php echo $totalPages; ?>;
            const totalMovies = <?php echo $totalMovies; ?>;

            function changePage(direction) {
                const newPage = currentPage + direction;
                if (newPage < 0 || newPage >= totalPages) return;

                currentPage = newPage;
                const startIndex = currentPage * moviesPerPage;
                const moviesToShow = allMovies.slice(startIndex, startIndex + moviesPerPage);
                const endIndex = Math.min(startIndex + moviesToShow.length, totalMovies);

                // Cập nhật danh sách phim
                const videoItems = document.getElementById('video-items');
                if (!videoItems) {
                    console.error('video-items element not found');
                    return;
                }
                videoItems.innerHTML = moviesToShow.length > 0 ? moviesToShow.map(movie => `
                    <div class="video-item">
                        <div class="relative">
                            <a href="<?php echo BASE_URL; ?>/Movie/detail/${movie.id}">
                                <img 
                                    src="${movie.bannerImage}" 
                                    alt="${movie.title}" 
                                    class="w-full h-44 object-cover"
                                    onerror="this.src='https://picsum.photos/1200/500'; this.onerror=null;"
                                >
                            </a>
                            <div class="play-icon">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>
                        <div class="video-content">
                            <p>${movie.title}</p>
                            <div class="rating-small">
                                <i class="fa-solid fa-star"></i> ${parseFloat(movie.averageRating).toFixed(1)} / 10
                            </div>
                        </div>
                    </div>
                `).join('') : '<p class="text-gray-400">Không có phim nào để hiển thị.</p>';

                // Cập nhật thông tin "Showing X - Y of Z"
                const showingInfo = document.getElementById('showing-info');
                if (!showingInfo) {
                    console.error('showing-info element not found');
                    return;
                }
                showingInfo.innerHTML = moviesToShow.length > 0 
                    ? `Showing ${startIndex + 1} - ${endIndex} of ${totalMovies}`
                    : 'No featured movies';

                // Cập nhật trạng thái nút mũi tên
                updateArrowVisibility();
            }

            function updateArrowVisibility() {
                const prevArrow = document.querySelector('.prev-arrow');
                const nextArrow = document.querySelector('.next-arrow');
                if (!prevArrow || !nextArrow) {
                    console.error('Navigation arrows not found');
                    return;
                }
                prevArrow.style.display = currentPage === 0 ? 'none' : 'block';
                nextArrow.style.display = currentPage === totalPages - 1 ? 'none' : 'block';

                // Gán lại sự kiện onclick cho các nút
                prevArrow.onclick = () => changePage(-1);
                nextArrow.onclick = () => changePage(1);
            }

            // Khởi tạo trạng thái ban đầu của nút mũi tên
            document.addEventListener('DOMContentLoaded', () => {
                console.log('DOM fully loaded for featured videos');
                updateArrowVisibility();
            }, { once: true });
        })();
    </script>
</div>