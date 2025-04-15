<?php
// Đảm bảo session được khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa BASE_URL nếu chưa có
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Movies</title>
    <!-- Liên kết CSS (giả sử bạn có tệp CSS tương tự như trong slider) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <!-- Font Awesome cho các biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(185px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .movie-card {
            position: relative;
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .movie-card:hover {
            transform: scale(1.05);
        }
        .movie-poster {
            width: 100%;
            height: 278px;
            object-fit: cover;
            cursor: pointer;
        }
        .card-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .add-watchlist {
            background: #f5c518;
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
        }
        .card-content {
            padding: 10px;
        }
        .card-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #f5c518;
        }
        .card-title {
            font-size: 1.1rem;
            margin: 5px 0;
            color: white;
        }
        .card-info {
            color: #999;
            font-size: 0.9rem;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .pagination a {
            padding: 8px 16px;
            background: #f5c518;
            color: black;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a:hover {
            background: #e0b015;
        }
        .pagination .disabled {
            background: #555;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<?php include 'app/views/utils/navbar.php'; ?>
    <div class="container">
        <div class="section-title">
            <h1>New Movies</h1>
        </div>
        
        <!-- Hiển thị danh sách phim -->
        <?php if (!empty($movies)): ?>
            <div class="movie-grid">
                <?php foreach ($movies as $movie): 
                    $movieId = isset($movie['id']) ? htmlspecialchars($movie['id']) : '';
                    $movieTitle = isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Untitled';
                    $poster = isset($movie['poster']) ? htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/185x278';
                    $rating = isset($movie['rating']) && $movie['rating'] !== '' && $movie['rating'] !== null ? number_format(floatval($movie['rating']), 1) : '0.0';
                    $releaseYear = isset($movie['releaseYear']) ? htmlspecialchars($movie['releaseYear']) : 'Unknown';
                ?>
                <div class="movie-card">
                    <img src="<?php echo $poster; ?>" 
                         alt="<?php echo $movieTitle; ?>" 
                         class="movie-poster" 
                         onclick="window.location.href='<?php echo BASE_URL; ?>/Movie/detail/<?php echo $movieId; ?>'">
                    <div class="card-content">
                        <div class="card-rating">
                            <i class="fas fa-star" style="color: #f5c518;"></i>
                            <span><?php echo $rating; ?></span>
                        </div>
                        <h3 class="card-title"><?php echo $movieTitle; ?></h3>
                        <div class="card-info"><?php echo $releaseYear; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No new movies found.</p>
        <?php endif; ?>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?php echo BASE_URL; ?>/movies/new?page=<?php echo $page - 1; ?>">Previous</a>
                <?php else: ?>
                    <a class="disabled">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?php echo BASE_URL; ?>/movies/new?page=<?php echo $i; ?>" 
                       <?php echo $i === $page ? 'style="background: #e0b015;"' : ''; ?>>
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="<?php echo BASE_URL; ?>/movies/new?page=<?php echo $page + 1; ?>">Next</a>
                <?php else: ?>
                    <a class="disabled">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript cho watchlist (nếu cần) -->
    <script>
        function addToWatchlist(movieId) {
            // Gửi yêu cầu AJAX để thêm vào watchlist (giả sử bạn đã có API)
            fetch('<?php echo BASE_URL; ?>/watchlist/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ movieId: movieId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'Added to watchlist!');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add to watchlist.');
            });
        }
    </script>
</body>
</html>