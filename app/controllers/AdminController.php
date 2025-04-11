<?php
include_once 'app/config/database.php';
include_once 'app/models/Movie.php';
include_once 'app/models/Actor.php';
include_once 'app/models/MovieActor.php';
include_once 'app/models/Genre.php';
include_once 'app/models/MovieGenre.php';
include_once 'app/middleware/AuthMiddleware.php';
class AdminController
{
    private $db;
    private $movie;
    private $actor;
    private $movieActor;
    private $genre;
    private $movieGenre;

    function index()
    {
        AuthMiddleware::checkAdmin();
        include "app/views/Admin/index.php";
    }
    function manageMovie()
    {
        AuthMiddleware::checkAdmin();
        include "app/views/Movie/manage_movie.php";
    }
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            // Gọi hàm addMovie để thực hiện thêm phim
            if ($this->movie->create()) {
                // Lấy movie id mới tạo ra
                $movieId = $this->movie->id;  // ID của movie mới tạo ra

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
                header('Location: /Movie_Project/Movie');
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
}
