<?php
// Đảm bảo BASE_URL được sử dụng để tạo liên kết
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Movie_Project');
}
?>

<footer class="imdb-footer bg-dark text-light">
    <div class="container">
        <div class="row">
            <!-- Logo hoặc tên dự án -->
            <div class="col-md-3 col-sm-12 mb-3">
                <a href="<?php echo BASE_URL; ?>/" class="footer-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/69/IMDB_Logo_2016.svg" alt="MovieDB Logo" class="logo-img">
                </a>
                <p class="mt-2">Your ultimate movie database for discovering films and actors.</p>
            </div>

            <!-- Liên kết hữu ích -->
            <div class="col-md-3 col-sm-6 mb-3">
                <h5>Explore</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/movies">Movies</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/actors">Actors</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/genres">Genres</a></li>
                </ul>
            </div>

            <!-- Liên kết hỗ trợ -->
            <div class="col-md-3 col-sm-6 mb-3">
                <h5>Support</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/help">Help Center</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contact">Contact Us</a></li>
                </ul>
            </div>

            <!-- Liên kết chính sách -->
            <div class="col-md-3 col-sm-12 mb-3">
                <h5>Policies</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/privacy">Privacy Policy</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/terms">Terms of Use</a></li>
                </ul>
            </div>
        </div>

        <!-- Bản quyền -->
        <div class="row">
            <div class="col-12 text-center mt-4">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> MovieDB. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<style>
.imdb-footer {
    padding: 40px 0 20px;
    border-top: 1px solid #333;
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
}

.imdb-footer .container {
    max-width: 1200px;
}

.imdb-footer .logo-img {
    width: 100px;
    filter: brightness(0) invert(1); /* Chuyển logo thành màu trắng để phù hợp nền tối */
}

.imdb-footer h5 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 15px;
    color: #f5c518; /* Màu vàng giống IMDb */
}

.imdb-footer ul {
    padding: 0;
    margin: 0;
}

.imdb-footer ul li {
    margin-bottom: 10px;
}

.imdb-footer ul li a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.2s;
}

.imdb-footer ul li a:hover {
    color: #f5c518; /* Màu vàng khi hover */
}

.imdb-footer p {
    color: #999;
    font-size: 13px;
}

@media (max-width: 767px) {
    .imdb-footer .logo-img {
        width: 80px;
    }

    .imdb-footer h5 {
        font-size: 14px;
    }

    .imdb-footer ul li a {
        font-size: 13px;
    }
}
</style>