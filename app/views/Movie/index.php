<?php
session_start();
$showWatchlist = false;
$watchlists = [];
if (isset($_SESSION['user_id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $watchlistModel = new Watchlist($db);
    $watchlistModel->userId = $_SESSION['user_id'];
    $watchlists = $watchlistModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
    $showWatchlist = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"> <!-- Thêm font Roboto -->

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Add margin-top to the container to avoid navbar overlapping */
        .container {
            width: 90%;
            margin: 20px auto;
            text-align: center;
            padding-top: 80px;
        }

        h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 40px;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            /* Giảm số thẻ trong hàng */
            gap: 20px;
            justify-items: center;
        }

        .movie-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            width: 100%;
            padding-bottom: 10px;
            max-width: 250px;
            /* Đảm bảo card không quá rộng */
        }

        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .movie-poster {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-bottom: 3px solid #007bff;
            cursor: pointer;
        }

        h3 {
            font-size: 1.2em;
            color: #fff;
            margin: 10px 0;
            min-height: 40px;
        }

        .rating {
            display: flex;
            justify-content: center;
            color: #f9a825;
            margin-top: 5px;
        }

        .rating i {
            margin-right: 5px;
        }

        .movie-card button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }

        .movie-card button:hover {
            background-color: #0056b3;
        }

        .movie-card .watchlist-btn {
            background-color: #444;
        }

        .movie-card .watchlist-btn:hover {
            background-color: #555;
        }

        /* Overlay for showing watchlist options */
        .watchlist-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            display: none;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-radius: 10px;
        }

        .movie-card:hover .watchlist-overlay {
            display: flex;
        }

        .watchlist-options {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .watchlist-options button {
            margin-bottom: 10px;
            background-color: #007bff;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
        }

        .watchlist-options button:hover {
            background-color: #0056b3;
        }

        .pagination {
            margin-top: 40px;
            text-align: center;
            display: flex;
            /* Thêm flexbox */
            justify-content: center;
            /* Căn giữa các nút */
            align-items: center;
            /* Căn giữa theo chiều dọc */
        }

        .pagination ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            /* Sử dụng flexbox để sắp xếp các phần tử */
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 16px;
            background-color: #f1f1f1;
            color: #007bff;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
        }

        .pagination a.disabled {
            background-color: #ddd;
            color: #aaa;
            cursor: not-allowed;
        }
    </style>

</head>

<body>
    <?php include 'app/views/Utils/Navbar.php'; ?>
    <div class="container" style="display: flex; gap: 20px;">
        <!-- Cột chính: Danh sách phim -->
        <div style="flex: 3;">
            <h1>Movie List</h1>

            <div class="movie-grid">
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                        <div class="movie-card">
                            <img src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title']; ?>" class="movie-poster" onclick="window.location.href='movie_detail.php?id=<?php echo $movie['id']; ?>'">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span><?php echo isset($movie['rating']) ? $movie['rating'] : 'N/A'; ?></span>
                            </div>
                            <h3 onclick="window.location.href='movie_detail.php?id=<?php echo $movie['id']; ?>'"><?php echo $movie['title']; ?></h3>
                            <button class="watchlist-btn" onclick="addToWatchlist(<?php echo $movie['id']; ?>)">+ Watchlist</button>
                            <button onclick="window.location.href='movie_trailer.php?id=<?php echo $movie['id']; ?>'">
                                <i class="fas fa-play"></i> Watch Trailer
                            </button>
                            <div class="watchlist-overlay">
                                <div class="watchlist-options">
                                    <button onclick="addToWatchlist(<?php echo $movie['id']; ?>)">Add to Watchlist</button>
                                    <button onclick="createWatchlist(<?php echo $movie['id']; ?>)">Create New Watchlist</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No movies found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($totalPages > 1): ?>
                    <ul>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li><a href="index.php?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cột phụ: Watchlist -->
        <?php if ($showWatchlist): ?>
            <div style="flex: 1; background-color: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h2 style="font-size: 1.5em; margin-bottom: 20px;">My Watchlist</h2>
                <?php if (!empty($watchlists)): ?>
                    <?php foreach ($watchlists as $watchlist): ?>
                        <div style="margin-bottom: 15px;">
                            <h4 style="font-size: 1.2em;"><?php echo $watchlist['name']; ?></h4>
                            <a href="/Movie_Project/Watchlist" style="color: #007bff; text-decoration: none;">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No watchlists found. Create one now!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function addToWatchlist(movieId) {
            window.location.href = '/Movie_Project/Watchlist/addToWatchlist/' + movieId;
        }

        function createWatchlist(movieId) {
            // Logic để tạo watchlist mới (có thể mở một modal để nhập tên watchlist)
            alert('Feature to create new watchlist coming soon!');
        }
    </script>
</body>

</html>