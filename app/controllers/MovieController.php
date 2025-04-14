<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include các file cần thiết
include_once 'app/config/database.php';
include_once 'app/models/Movie.php';
include_once 'app/models/Actor.php';
include_once 'app/models/MovieActor.php';
include_once 'app/models/Genre.php';
include_once 'app/models/MovieGenre.php';
include_once 'app/models/Watchlist.php';
include_once 'app/middleware/AuthMiddleware.php';


class MovieController
{
    private $db;
    private $movie;
    private $actor;
    private $movieActor;
    private $genre;
    private $movieGenre;
    private $watchlist;

    public function __construct()
    {
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->movie = new Movie($this->db);
        $this->actor = new Actor($this->db);
        $this->movieActor = new MovieActor($this->db);
        $this->genre = new Genre($this->db);
        $this->movieGenre = new MovieGenre($this->db);
        $this->watchlist = new Watchlist($this->db);
    }

    // Hiển thị danh sách phim
    public function index()
    {
        $limit = 12; // Số lượng phim trên mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Lấy danh sách phim với actors và genres liên quan
        $movies = $this->movie->getMoviesWithDetails($limit, $offset)->fetchAll(PDO::FETCH_ASSOC);
        $totalMovies = $this->movie->getTotalMovies();
        $totalPages = ceil($totalMovies / $limit);

        // Lấy danh sách Watchlist của người dùng (nếu đã đăng nhập)
        session_start();
        $showWatchlist = false;
        $watchlists = [];
        if (isset($_SESSION['user_id'])) {
            $this->watchlist->userId = $_SESSION['user_id'];
            $watchlists = $this->watchlist->getAll()->fetchAll(PDO::FETCH_ASSOC);
            $showWatchlist = true;
        }

        include 'app/views/Movie/index.php'; // Tạo file view để hiển thị
    }

    // Quản lý danh sách phim
    public function manageMovie()
    {
        AuthMiddleware::checkAdmin();
        $limit = 10; // Số lượng phim trên mỗi trang trong quản lý admin
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Lấy danh sách phim với actors và genres
        $movies = $this->movie->getMoviesWithDetails($limit, $offset)->fetchAll(PDO::FETCH_ASSOC);
        $totalMovies = $this->movie->getTotalMovies();
        $totalPages = ceil($totalMovies / $limit);

        include 'app/views/Movie/manage_movie.php'; // Truyền dữ liệu qua view
    }
    // Tạo mới movie
    public function add()
    {
        AuthMiddleware::checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check the form data sent
            var_dump($_POST); // Debug the data being sent

            // Prepare data for the movie
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'releaseYear' => $_POST['releaseYear'],
                'director' => $_POST['director'],
                'poster' => $_POST['poster'],
                'bannerImage' => $_POST['bannerImage'],
                'trailer' => $_POST['trailer'], // Add the trailer data
                'theatricalReleaseDate' => $_POST['theatricalReleaseDate'], // Add the theatricalReleaseDate data
                'actors' => isset($_POST['actors']) ? $_POST['actors'] : [],
                'genres' => isset($_POST['genres']) ? $_POST['genres'] : []
            ];

            var_dump($data); // Debug the data after processing

            // Call the create function to add the movie
            if ($this->movie->create($data)) {
                // Get the movie ID of the newly added movie
                $movieId = $this->movie->id;

                // Add MovieActor and MovieGenre relationships
                if (isset($data['actors']) && !empty($data['actors'])) {
                    foreach ($data['actors'] as $actorId) {
                        $this->movieActor->movieId = $movieId;
                        $this->movieActor->actorId = $actorId;
                        $this->movieActor->create();
                    }
                }

                if (isset($data['genres']) && !empty($data['genres'])) {
                    foreach ($data['genres'] as $genreId) {
                        $this->movieGenre->movieId = $movieId;
                        $this->movieGenre->genreId = $genreId;
                        $this->movieGenre->create();
                    }
                }

                $_SESSION['success'] = 'Movie added successfully';
                header('Location: /Movie_Project/Admin');
                exit;
            } else {
                $_SESSION['error'] = 'Error occurred while adding movie';
            }
        }

        // Fetch actors and genres for dropdowns
        $actors = $this->actor->getAll()->fetchAll(PDO::FETCH_ASSOC);
        $genres = $this->genre->getAll()->fetchAll(PDO::FETCH_ASSOC);

        // Include the form view to display
        include 'app/views/Movie/add.php';
    }


    // Edit movie details
    public function update($movieId)
    {
        AuthMiddleware::checkAdmin();
        // Lấy thông tin movie theo ID
        $this->movie->id = $movieId;
        $movie = $this->movie->getById();  // Lấy dữ liệu movie hiện tại từ database

        if (!$movie) {
            $_SESSION['error'] = 'Movie does not exist.';
            header('Location: /Movie_Project/Admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data including trailer and theatricalReleaseDate
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'releaseYear' => $_POST['releaseYear'],
                'director' => $_POST['director'],
                'poster' => $_POST['poster'],
                'bannerImage' => $_POST['bannerImage'],
                'trailer' => isset($_POST['trailer']) ? $_POST['trailer'] : '',  // Handle trailer input
                'theatricalReleaseDate' => isset($_POST['theatricalReleaseDate']) ? $_POST['theatricalReleaseDate'] : '',  // Handle theatricalReleaseDate input
                'actors' => isset($_POST['actors']) ? $_POST['actors'] : [],
                'genres' => isset($_POST['genres']) ? $_POST['genres'] : []
            ];

            // Check if the movie was successfully updated
            if ($this->movie->update($data)) {
                // Xóa các actors và genres cũ
                $this->movieActor->deleteByMovieId($movieId);
                $this->movieGenre->deleteByMovieId($movieId);

                // Thêm actors mới
                if (!empty($data['actors'])) {
                    foreach ($data['actors'] as $actorId) {
                        $this->movieActor->movieId = $movieId;
                        $this->movieActor->actorId = $actorId;
                        $this->movieActor->create(); // Thêm MovieActor vào bảng phụ
                    }
                }

                // Thêm genres mới
                if (!empty($data['genres'])) {
                    foreach ($data['genres'] as $genreId) {
                        $this->movieGenre->movieId = $movieId;
                        $this->movieGenre->genreId = $genreId;
                        $this->movieGenre->create(); // Thêm MovieGenre vào bảng phụ
                    }
                }

                $_SESSION['success'] = 'Movie updated successfully';
                header('Location: /Movie_Project/Admin');
                exit;
            } else {
                $_SESSION['error'] = 'Error occurred while updating movie';
            }
        }

        // Lấy danh sách actors và genres để hiển thị trong dropdown
        $actors = $this->actor->getAll()->fetchAll(PDO::FETCH_ASSOC);
        $genres = $this->genre->getAll()->fetchAll(PDO::FETCH_ASSOC);

        // Lấy danh sách actors và genres đã được gán cho movie
        $assignedActorIds = $this->movieActor->getActorsByMovieId($movieId);
        $assignedGenreIds = $this->movieGenre->getGenresByMovieId($movieId);

        // Truyền dữ liệu vào view
        include 'app/views/Movie/edit.php';
    }


    // Chi tiết movie
    public function detail($movieId)
    {
        $this->movie->id = $movieId;
        $movie = $this->movie->getById();

        if ($movie) {
            // Lấy danh sách actors liên quan
            $actors = $this->actor->getActorsByMovieId($movieId);

            // Lấy danh sách genres liên quan
            $genres = $this->genre->getGenresByMovieId($movieId);

            // Lấy đánh giá trung bình của phim
            $ratingData = $this->movie->getAverageRating($movieId);
            $averageRating = $ratingData['average'];
            $ratingCount = $ratingData['count'];

            // Lấy đánh giá của người dùng hiện tại (nếu có)
            $userRating = 0;
            if (isset($_SESSION['user_id'])) {
                $userRating = $this->movie->getUserRating($movieId, $_SESSION['user_id']);
            }

            // Lấy danh sách phim nổi bật (Featured Videos)
            $limit = 10; // Số lượng phim tối đa
            $offset = 0; // Bắt đầu từ phim đầu tiên
            $featuredStmt = $this->movie->getMoviesByPage($limit, $offset);
            $featuredMovies = [];
            while ($row = $featuredStmt->fetch(PDO::FETCH_ASSOC)) {
                $movieRatingData = $this->movie->getAverageRating($row['id']);
                $featuredMovies[] = [
                    'id' => $row['id'],
                    'bannerImage' => $row['bannerImage'],
                    'title' => $row['title'],
                    'averageRating' => $movieRatingData['average'],
                ];
            }

            // Truyền dữ liệu vào view
            include 'app/views/Movie/detail.php';
        } else {
            $_SESSION['error'] = "Không tìm thấy phim với ID: $movieId";
            header('Location:/Movie_Project/Movie');
            exit();
        }
    }

    // Hành động đánh giá phim
    public function rate($movieId)
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá phim.']);
            exit();
        }

        // Kiểm tra yêu cầu có phải POST không
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
            exit();
        }

        // Lấy dữ liệu từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        $rating = isset($data['rating']) ? (int)$data['rating'] : 0;

        // Kiểm tra rating hợp lệ (1-10)
        if ($rating < 1 || $rating > 10) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Điểm đánh giá phải từ 1 đến 10.']);
            exit();
        }

        // Lưu đánh giá vào database
        $userId = $_SESSION['user_id'];
        $success = $this->movie->rateMovie($movieId, $userId, $rating);

        if ($success) {
            // Lấy lại đánh giá trung bình và số lượng đánh giá
            $ratingData = $this->movie->getAverageRating($movieId);
            $averageRating = $ratingData['average'];
            $ratingCount = $ratingData['count'];

            echo json_encode([
                'success' => true,
                'message' => 'Đánh giá thành công!',
                'averageRating' => $averageRating,
                'ratingCount' => $ratingCount,
                'userRating' => $rating
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi lưu đánh giá.']);
        }
        exit();
    }

    // Xóa movie
    public function delete($movieId)
    {
        AuthMiddleware::checkAdmin();
        $this->movie->id = $movieId;
        if ($this->movie->delete()) {
            header('Location: /Movie_Project/Movie/manageMovie');
            exit;
        } else {
            echo "Lỗi khi xóa movie.";
        }
    }

    // Tìm kiếm phim
    public function search()
    {
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';
        if (empty($query)) {
            $_SESSION['error'] = 'Please enter a search query.';
            header('Location: /Movie_Project/Movie');
            exit;
        }

        $limit = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Tìm kiếm phim theo tiêu đề
        $stmt = $this->db->prepare("
            SELECT m.*, 
                   GROUP_CONCAT(DISTINCT g.name) AS genre_names, 
                   GROUP_CONCAT(DISTINCT a.name) AS actor_names 
            FROM Movie m
            LEFT JOIN MovieGenre mg ON m.id = mg.movieId
            LEFT JOIN Genre g ON mg.genreId = g.id
            LEFT JOIN MovieActor ma ON m.id = ma.movieId
            LEFT JOIN Actor a ON ma.actorId = a.id
            WHERE m.title LIKE :query
            GROUP BY m.id
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStmt = $this->db->prepare("SELECT COUNT(*) as total FROM Movie WHERE title LIKE :query");
        $totalStmt->bindValue(':query', '%' . $query . '%');
        $totalStmt->execute();
        $totalMovies = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalMovies / $limit);

        include 'app/views/Movie/index.php';
    }
}
