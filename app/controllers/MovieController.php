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
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra dữ liệu gửi lên từ form
            var_dump($_POST); // Thêm dòng này để kiểm tra dữ liệu gửi lên
            
            // Lấy thông tin từ form
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'releaseYear' => $_POST['releaseYear'],
                'director' => $_POST['director'],
                'poster' => $_POST['poster'],
                'bannerImage' => $_POST['bannerImage'],
                'actors' => isset($_POST['actors']) ? $_POST['actors'] : [],
                'genres' => isset($_POST['genres']) ? $_POST['genres'] : []
            ];
    
            var_dump($data); // Kiểm tra dữ liệu sau khi xử lý
            
    
            // Gọi hàm addMovie để thực hiện thêm phim
            if ($this->movie->create($data)) {
                // Lấy movie id mới tạo ra
                $movieId = $this->movie->id;
    
                // Thêm MovieActor và MovieGenre
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
    
        // Lấy danh sách actors và genres để hiển thị trong dropdown
        $actors = $this->actor->getAll()->fetchAll(PDO::FETCH_ASSOC);
        $genres = $this->genre->getAll()->fetchAll(PDO::FETCH_ASSOC);
    
        include 'app/views/Movie/add.php';
    }
    
    // Edit movie details
    public function update($movieId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy thông tin từ form
            $this->movie->id = $movieId;
            $this->movie->title = $_POST['title'];
            $this->movie->description = $_POST['description'];
            $this->movie->releaseYear = $_POST['releaseYear'];
            $this->movie->director = $_POST['director'];
            $this->movie->poster = $_POST['poster'];
            $this->movie->bannerImage = $_POST['bannerImage'];

            // Xử lý upload ảnh nếu có (chỉ cần cho bannerImage hoặc poster)
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/images/'; // Đường dẫn đến thư mục images
                $uploadFile = $uploadDir . basename($_FILES['poster']['name']);

                // Kiểm tra kiểu file và kích thước file
                $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "Kiểu file không hợp lệ.";
                    return;
                }

                if ($_FILES['poster']['size'] > 500000) {
                    echo "Kích thước file quá lớn.";
                    return;
                }

                if (move_uploaded_file($_FILES['poster']['tmp_name'], $uploadFile)) {
                    $this->movie->poster = 'assets/images/' . basename($_FILES['poster']['name']); // Lưu đường dẫn
                } else {
                    echo "Lỗi khi upload ảnh poster.";
                    return;
                }
            }

            // Cập nhật movie trong cơ sở dữ liệu
            if ($this->movie->update()) {
                // Cập nhật MovieActor (Diễn viên)
                $this->movieActor->deleteByMovieId($movieId);  // Xóa các diễn viên cũ
                if (isset($_POST['actors']) && !empty($_POST['actors'])) {
                    foreach ($_POST['actors'] as $actorId) {
                        $this->movieActor->movieId = $movieId;
                        $this->movieActor->actorId = $actorId;
                        $this->movieActor->create();  // Thêm lại các diễn viên vào bảng phụ
                    }
                }

                // Cập nhật MovieGenre (Thể loại)
                $this->movieGenre->deleteByMovieId($movieId);  // Xóa các thể loại cũ
                if (isset($_POST['genres']) && !empty($_POST['genres'])) {
                    foreach ($_POST['genres'] as $genreId) {
                        $this->movieGenre->movieId = $movieId;
                        $this->movieGenre->genreId = $genreId;
                        $this->movieGenre->create();  // Thêm lại các thể loại vào bảng phụ
                    }
                }

                // Chuyển hướng hoặc hiển thị thông báo thành công
                $_SESSION['success'] = 'Movie updated successfully';
                header('Location: /Movie_Project/Movie');
                exit;
            } else {
                $_SESSION['error'] = 'Error occurred while updating movie';
            }
        } else {
            // Lấy thông tin movie từ cơ sở dữ liệu
            $this->movie->id = $movieId;
            $movie = $this->movie->getById();

            // Lấy danh sách actors và genres hiện tại
            $actors = $this->actor->getAll()->fetchAll(PDO::FETCH_ASSOC);
            $genres = $this->genre->getAll()->fetchAll(PDO::FETCH_ASSOC);

            // Lấy danh sách actors và genres đã gán cho movie này
            $assignedActors = $this->actor->getActorsByMovieId($movieId);
            $assignedActorIds = array_column($assignedActors, 'id');

            $assignedGenres = $this->genre->getGenresByMovieId($movieId);
            $assignedGenreIds = array_column($assignedGenres, 'id');

            // Hiển thị form chỉnh sửa thông tin movie
            include 'app/views/Movie/edit.php';  // Gọi view chỉnh sửa
        }
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
        $this->movie->id = $movieId;
        if ($this->movie->delete()) {
            header('Location: /Movie_Project/Movie');
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
