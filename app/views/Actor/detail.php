<?php
// app/views/actor/detail.php

// Giả lập dữ liệu cho "More to Explore"
$relatedItems = [
    ['title' => 'Piratas Del Caribe', 'image' => 'https://th.bing.com/th/id/OIP.gF--XR-CwFzNmC-zfsJD1QHaKu?w=208&h=300&c=7&r=0&o=5&dpr=1.3&pid=1.7'],
    ['title' => 'Blue Beetle Movie', 'image' => 'https://th.bing.com/th/id/OIP.2_wH2qmbdU02QwQufqklsAHaNK?w=187&h=333&c=7&r=0&o=5&dpr=1.3&pid=1.7'],
    ['title' => 'Black Adam', 'image' => 'https://th.bing.com/th/id/R.9df5b5fa360bc7db74ae421bc7219585?rik=aFKy7rniM34ZGQ&riu=http%3a%2f%2fwww.impawards.com%2f2022%2fposters%2fblack_adam_ver10_xxlg.jpg&ehk=VgB%2bT%2bUWSmVQrBgOTCsIpcQp1oIaevElWf60FhMci4M%3d&risl=&pid=ImgRaw&r=0'],
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($actor['name']) ? htmlspecialchars($actor['name']) : 'Unknown Actor'; ?> - MovieDB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link href="/Movie_Project/public/css/actor/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/utils/navbar.php'; ?>

    <main class="main-content bg-dark">
        <div class="container actor-detail-container">
            <div class="actor-detail">
                <!-- Phần tiêu đề và thông tin -->
                <div class="actor-header">
                    <img src="<?php echo isset($actor['profileImage']) ? htmlspecialchars($actor['profileImage']) : 'https://via.placeholder.com/185x278'; ?>" 
                         alt="<?php echo isset($actor['name']) ? htmlspecialchars($actor['name']) : 'Unknown Actor'; ?>" 
                         class="actor-image">
                    <div class="actor-info">
                        <h1><?php echo isset($actor['name']) ? htmlspecialchars($actor['name']) : 'Unknown Actor'; ?></h1>
                        <p class="actor-roles">Diễn viên</p>
                        <p><strong>Ngày Sinh:</strong> <?php echo isset($actor['birthDate']) ? htmlspecialchars($actor['birthDate']) : 'Unknown'; ?></p>
                        <p><strong>Nơi Sinh:</strong> <?php echo isset($actor['birthPlace']) ? htmlspecialchars($actor['birthPlace']) : 'Unknown'; ?></p>
                        <p><strong>Mô Tả:</strong> <?php echo isset($actor['description']) && $actor['description'] !== '' ? htmlspecialchars($actor['description']) : 'Không có mô tả.'; ?></p>
                    </div>
                </div>

                <!-- Phần Known For -->
                <section class="known-for">
                    <div class="section-title">
                        <h2>Được Biết Đến Với</h2>
                    </div>
                    <div class="movie-list">
                        <?php if (!empty($movies)): ?>
                            <?php 
                            // Limit to 4 movies for display
                            $displayMovies = array_slice($movies, 0, 4);
                            foreach ($displayMovies as $movie): 
                            ?>
                                <div class="movie-item">
                                    <div class="movie-poster-wrapper">
                                        <img src="<?php echo htmlspecialchars($movie['poster'] ?: '/Movie_Project/public/images/default_poster.jpg'); ?>" 
                                            alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                            onclick="window.location.href='/Movie_Project/Movie/detail/<?php echo htmlspecialchars($movie['id']); ?>'" 
                                            class="movie-poster">
                                        <button class="add-watchlist" onclick="addToWatchlist('<?php echo htmlspecialchars($movie['id']); ?>')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="movie-info">
                                        <div class="movie-rating">
                                            <i class="fas fa-star"></i>
                                            <span><?php echo 'N/A'; // Placeholder for rating ?></span>
                                        </div>
                                        <span class="movie-type"><?php echo 'Movie'; // Placeholder for type ?></span>
                                        <h3 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                        <p class="movie-director"><?php echo isset($movie['director']) ? htmlspecialchars($movie['director']) : 'Unknown'; ?></p>
                                        <p class="movie-release"><?php echo isset($movie['releaseYear']) && $movie['releaseYear'] > 0 ? htmlspecialchars($movie['releaseYear']) : 'Unknown'; ?></p>
                                        <a href='/Movie_Project/Movie/detail/<?php echo htmlspecialchars($movie['id']); ?>' class="info-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Diễn viên này chưa có phim nào được biết đến.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Phần More to Explore (Sidebar) -->
            <aside class="actor-sidebar">
                <div class="section-title">
                    <h2>Khám Phá Thêm</h2>
                </div>
                <div class="related-items">
                    <?php foreach ($relatedItems as $item): ?>
                        <div class="related-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <div class="related-info">
                                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                <a href="#" class="see-gallery">Xem bộ sưu tập</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>