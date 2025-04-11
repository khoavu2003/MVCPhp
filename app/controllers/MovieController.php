<?php
// Include các file cần thiết
include_once 'app/config/database.php';
include_once 'app/models/Movie.php';
include_once 'app/models/Actor.php';
include_once 'app/models/MovieActor.php';
include_once 'app/models/Genre.php';
include_once 'app/models/MovieGenre.php';
include_once 'app/middleware/AuthMiddleware.php';


class MovieController
{
    private $db;
    private $movie;
    private $actor;
    private $movieActor;
    private $genre;
    private $movieGenre;

    public function __construct()
    {
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->movie = new Movie($this->db);
        $this->actor = new Actor($this->db);
        $this->movieActor = new MovieActor($this->db);
        $this->genre = new Genre($this->db);
        $this->movieGenre = new MovieGenre($this->db);
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

        include 'app/views/Movie/detail.php'; // Tạo view chi tiết để hiển thị
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
}
