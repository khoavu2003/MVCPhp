<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Watchlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Link tới file CSS mới tách ra -->
    <link href="/Movie_Project/public/css/watchlist/watchlist.css" rel="stylesheet" />
</head>

<body>
    <?php require_once 'app/views/Utils/Navbar.php'; ?>
    <div class="watchlist-page">
        <div class="container">
            <!-- Tạo danh sách mới -->
            <div class="form-wrapper">
                <h4 class="mb-3 text-white">Tạo danh sách mới</h4>
                <form action="/Movie_Project/Watchlist/create" method="POST" class="text-white">

                    <div class="mb-3 ">
                        <input type="text" name="name" class="form-control" placeholder="Tên danh sách..." required />
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" rows="2" placeholder="Mô tả (tùy chọn)..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-yellow">+ Tạo danh sách</button>
                </form>
            </div>

            <!-- Danh sách hiện có -->
            <?php if (!empty($watchlists)): ?>
                <?php foreach ($watchlists as $watchlist): ?>
                    <div class="watchlist-item">
                        <h5>
                            <a href="/Movie_Project/Watchlist/movies/<?php echo $watchlist['id']; ?>" class="text-white text-decoration-none">
                                <?php echo htmlspecialchars($watchlist['name']); ?>
                            </a>
                        </h5>
                        <p class="text-muted"><?php echo htmlspecialchars($watchlist['description'] ?? ''); ?></p>
                        <a href="/Movie_Project/Watchlist/delete/<?php echo $watchlist['id']; ?>" class="btn btn-red" onclick="return confirm('Xóa danh sách này?')">
                            Xóa danh sách
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Bạn chưa có danh sách nào.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>