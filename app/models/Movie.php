<?php

class Movie {
    private $conn;
    private $table_name = "Movie";

    public $id;
    public $title;
    public $description;
    public $releaseYear;
    public $director;
    public $poster;
    public $trailer;
    public $theatricalReleaseDate;
    public $bannerImage;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả phim
    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy phim theo ID
    function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->releaseYear = $row['releaseYear'];
            $this->director = $row['director'];
            $this->poster = $row['poster'];
            $this->trailer = $row['trailer'];
            $this->theatricalReleaseDate = $row['theatricalReleaseDate'];
            $this->bannerImage = $row['bannerImage'];
            return $row;
        }
        return null;
    }

    // Tạo phim mới
    public function getMoviesWithDetails($limit, $offset) {
        $query = "
        SELECT m.*, 
               GROUP_CONCAT(DISTINCT g.name) AS genre_names, 
               GROUP_CONCAT(DISTINCT a.name) AS actor_names 
        FROM Movie m
        LEFT JOIN MovieGenre mg ON m.id = mg.movieId
        LEFT JOIN Genre g ON mg.genreId = g.id
        LEFT JOIN MovieActor ma ON m.id = ma.movieId
        LEFT JOIN Actor a ON ma.actorId = a.id
        GROUP BY m.id
        LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới movie
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET title=:title, description=:description, releaseYear=:releaseYear, 
                      director=:director, poster=:poster, trailer=:trailer, 
                      theatricalReleaseDate=:theatricalReleaseDate, bannerImage=:bannerImage";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->releaseYear = htmlspecialchars(strip_tags($this->releaseYear));
        $this->director = htmlspecialchars(strip_tags($this->director));
        $this->poster = htmlspecialchars(strip_tags($this->poster));
        $this->trailer = htmlspecialchars(strip_tags($this->trailer));
        $this->theatricalReleaseDate = htmlspecialchars(strip_tags($this->theatricalReleaseDate));
        $this->bannerImage = htmlspecialchars(strip_tags($this->bannerImage));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":releaseYear", $this->releaseYear);
        $stmt->bindParam(":director", $this->director);
        $stmt->bindParam(":poster", $this->poster);
        $stmt->bindParam(":trailer", $this->trailer);
        $stmt->bindParam(":theatricalReleaseDate", $this->theatricalReleaseDate);
        $stmt->bindParam(":bannerImage", $this->bannerImage);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // Cập nhật phim
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, description=:description, releaseYear=:releaseYear, 
                      director=:director, poster=:poster, trailer=:trailer, 
                      theatricalReleaseDate=:theatricalReleaseDate, bannerImage=:bannerImage 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->releaseYear = htmlspecialchars(strip_tags($this->releaseYear));
        $this->director = htmlspecialchars(strip_tags($this->director));
        $this->poster = htmlspecialchars(strip_tags($this->poster));
        $this->trailer = htmlspecialchars(strip_tags($this->trailer));
        $this->theatricalReleaseDate = htmlspecialchars(strip_tags($this->theatricalReleaseDate));
        $this->bannerImage = htmlspecialchars(strip_tags($this->bannerImage));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":releaseYear", $this->releaseYear);
        $stmt->bindParam(":director", $this->director);
        $stmt->bindParam(":poster", $this->poster);
        $stmt->bindParam(":trailer", $this->trailer);
        $stmt->bindParam(":theatricalReleaseDate", $this->theatricalReleaseDate);
        $stmt->bindParam(":bannerImage", $this->bannerImage);

        return $stmt->execute();
    }

    // Xóa phim
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
    function getMoviesByPage($limit, $offset) {
        $query = "SELECT * FROM " . $this->table_name . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }

    // Lấy tổng số phim trong cơ sở dữ liệu
    function getTotalMovies() {
        $query = "SELECT COUNT(*) as total_movies FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_movies'];
    }
}
?>
