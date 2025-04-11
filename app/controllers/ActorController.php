<?php
require_once 'app/config/database.php';
require_once 'app/models/Actor.php';
require_once 'app/models/Movie.php';

class ActorController {
    private $db;
    private $actor;
    private $movie;

    public function __construct() {
        $this->db = new Database();
        $this->actor = new Actor($this->db->getConnection());
        $this->movie = new Movie($this->db->getConnection());
    }

    public function detail($id) {
        $this->actor->id = $id;
        $actor = $this->actor->getById();

        if (!$actor) {
            header('HTTP/1.0 404 Not Found');
            require_once 'app/views/errors/404.php';
            exit;
        }

        $movies = $this->movie->getMoviesByActorId($id);
        require_once 'app/views/actor/detail.php';
    }
}