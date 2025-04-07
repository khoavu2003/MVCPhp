

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <!-- Link đến file CSS đã tách -->
    <link href="/Movie_Project/public/css/navbar/style.css" rel="stylesheet">
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="/Movie_Project">
      <img src="path_to_logo/logo.png" alt="IMDb" class="h-8 w-auto">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <button class="btn btn-dark" onclick="toggleMenu()">Menu</button>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Movies</a></li>
            <li><a class="dropdown-item" href="#">TV Shows</a></li>
            <li><a class="dropdown-item" href="#">Awards & Events</a></li>
          </ul>
        </li>
      </ul>

      <!-- Nút Login/Logout và chào mừng user -->
      <div class="d-flex ms-auto"> <!-- ms-auto để căn phải -->
        <?php
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (isset($_SESSION['user_id'])) {
   
            // Người dùng đã đăng nhập
            echo "<span class='navbar-text text-light me-3'>Chào mừng, " . $_SESSION['user_name'] . "!</span>";
            echo "<a href='/Movie_Project/Login/logout' class='btn btn-outline-light'>Logout</a>"; // Nút Logout
        } else {
            // Người dùng chưa đăng nhập
            echo "<a href='/Movie_Project/Login' class='btn btn-outline-light'>Login</a>"; // Nút Login
        }
        ?>
      </div>
    </div>
  </div>
</nav>

<!-- Mobile menu -->
<div id="mobileMenu" class="d-lg-none">
  <button class="btn btn-dark" onclick="toggleMobileMenu()">Toggle Mobile Menu</button>
</div>

<!-- Menu Component (should be handled by JavaScript) -->
<div id="menuComponent" class="d-none">
  <!-- Add your menu component content here -->
</div>

<script>
  function toggleMenu() {
    // Toggle Menu visibility
    document.getElementById('menuComponent').classList.toggle('d-none');
  }

  function toggleMobileMenu() {
    // Toggle Mobile Menu visibility
    document.getElementById('navbarNav').classList.toggle('show');
  }
</script>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
