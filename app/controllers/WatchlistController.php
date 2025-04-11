<?php
// Include file Database.php để kết nối CSDL
include_once 'app/config/database.php';
// Include các model cần thiết
include_once 'app/models/Watchlist.php';
include_once 'app/models/WatchlistMovies.php';
include_once 'app/models/Movie.php';

class WatchlistController
{
    private $db;
    private $watchlist;
    private $watchlistMovies;
    private $movie;

    public function __construct()
    {
        // Kết nối đến cơ sở dữ liệu
        $database = new Database();
        $this->db = $database->getConnection();
        $this->watchlist = new Watchlist($this->db);
        $this->watchlistMovies = new WatchlistMovies($this->db);
        $this->movie = new Movie($this->db);
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to view your watchlist.';
            header('Location: /Movie_Project/Login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $this->watchlist->userId = $userId;
        $watchlists = $this->watchlist->getAll()->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/Watchlist/index.php';
    }

    // Thêm phương thức movies()
    public function movies($watchlistId)
    {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to view your watchlist.';
            header('Location: /Movie_Project/Login');
            exit;
        }
        unset($_SESSION['error']);

        $userId = $_SESSION['user_id'];

        // Kiểm tra watchlistId có hợp lệ không
        if (!is_numeric($watchlistId) || $watchlistId <= 0) {
            $_SESSION['error'] = 'Invalid watchlist ID.';
            header('Location: /Movie_Project/Watchlist');
            exit;
        }

        $this->watchlist->id = $watchlistId;
        $watchlist = $this->watchlist->getById();

        // Debug: Kiểm tra giá trị
        if (!$watchlist) {
            $_SESSION['error'] = 'Watchlist not found (ID: ' . $watchlistId . ').';
            header('Location: /Movie_Project/Watchlist');
            exit;
        }

        // Debug: So sánh userId
        if ($watchlist['userId'] != $userId) {
            $_SESSION['error'] = 'You do not have permission to view this watchlist (Watchlist userId: ' . $watchlist['userId'] . ', Current userId: ' . $userId . ').';
            header('Location: /Movie_Project/Watchlist');
            exit;
        }

        // Lấy danh sách phim trong Watchlist
        $movies = $this->watchlist->getMovies();

        include 'app/views/Watchlist/movies.php';
    }

    // Sửa phương thức addToWatchlist()
    public function addToWatchlist($movieId, $watchlistId = null)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to add to watchlist.';
            header('Location: /Movie_Project/Login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // Nếu không có watchlistId, lấy hoặc tạo Default Watchlist
        if (!$watchlistId) {
            $this->watchlist->userId = $userId;
            $watchlistId = $this->watchlist->getOrCreateDefault();
        }

        // Kiểm tra xem phim đã có trong Watchlist chưa để tránh trùng lặp
        $stmt = $this->db->prepare("SELECT * FROM WatchlistMovies WHERE watchlistId = :watchlistId AND movieId = :movieId");
        $stmt->bindParam(':watchlistId', $watchlistId);
        $stmt->bindParam(':movieId', $movieId);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'Movie is already in this watchlist.';
            header('Location: /Movie_Project/Movie');
            exit;
        }

        // Thêm phim vào Watchlist
        $this->watchlistMovies->watchlistId = $watchlistId;
        $this->watchlistMovies->movieId = $movieId;
        if ($this->watchlistMovies->create()) {
            $_SESSION['success'] = 'Movie added to watchlist successfully.';
        } else {
            $_SESSION['error'] = 'Failed to add movie to watchlist.';
        }

        header('Location: /Movie_Project/Movie');
        exit;
    }

    public function removeFromWatchlist($watchlistId, $movieId)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to remove from watchlist.';
            header('Location: /Movie_Project/Login');
            exit;
        }

        $this->watchlistMovies->watchlistId = $watchlistId;
        $this->watchlistMovies->movieId = $movieId;
        $stmt = $this->db->prepare("DELETE FROM WatchlistMovies WHERE watchlistId = :watchlistId AND movieId = :movieId");
        $stmt->bindParam(':watchlistId', $watchlistId);
        $stmt->bindParam(':movieId', $movieId);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Movie removed from watchlist successfully.';
        } else {
            $_SESSION['error'] = 'Failed to remove movie from watchlist.';
        }

        header('Location: /Movie_Project/Watchlist');
        exit;
    }

    public function create()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to create a watchlist.';
            header('Location: /Movie_Project/Login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            // Kiểm tra dữ liệu đầu vào
            if (empty($name)) {
                $_SESSION['error'] = 'Watchlist name is required.';
                header('Location: /Movie_Project/Watchlist');
                exit;
            }

            // Tạo Watchlist mới
            $this->watchlist->userId = $userId;
            $this->watchlist->name = $name;
            $this->watchlist->description = $description;

            if ($this->watchlist->create()) {
                $_SESSION['success'] = 'Watchlist created successfully.';
            } else {
                $_SESSION['error'] = 'A watchlist with this name already exists or the name "Default Watchlist" is reserved.';
            }

            header('Location: /Movie_Project/Watchlist');
            exit;
        }
    }

    public function delete($watchlistId)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to delete a watchlist.';
            header('Location: /Movie_Project/Login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $this->watchlist->id = $watchlistId;

        // Kiểm tra xem Watchlist có thuộc về user hiện tại không
        $watchlist = $this->watchlist->getById();
        if (!$watchlist || $watchlist['userId'] != $userId) {
            $_SESSION['error'] = 'You do not have permission to delete this watchlist.';
            header('Location: /Movie_Project/Watchlist');
            exit;
        }

        // Xóa Watchlist
        if ($this->watchlist->delete()) {
            $_SESSION['success'] = 'Watchlist deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete watchlist.';
        }

        header('Location: /Movie_Project/Watchlist');
        exit;
    }
}
