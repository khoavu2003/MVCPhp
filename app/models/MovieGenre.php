<?php

class MovieGenre {
    private $conn;
    private $table_name = "MovieGenre";

    public $id;
    public $movieId;
    public $genreId;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả MovieGenre
    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy theo ID
    function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->movieId = $row['movieId'];
            $this->genreId = $row['genreId'];
            return $row;
        }
        return null;
    }

    // Tạo mới MovieGenre
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET movieId=:movieId, genreId=:genreId";
        $stmt = $this->conn->prepare($query);

        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->genreId = htmlspecialchars(strip_tags($this->genreId));

        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":genreId", $this->genreId);

        return $stmt->execute();
    }

    // Cập nhật MovieGenre
    function update() {
        $query = "UPDATE " . $this->table_name . " SET movieId=:movieId, genreId=:genreId WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->genreId = htmlspecialchars(strip_tags($this->genreId));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":genreId", $this->genreId);

        return $stmt->execute();
    }

    // Xóa MovieGenre
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
    function getGenresByMovieId($movieId) {
        $query = "
            SELECT g.id, g.name
            FROM " . $this->table_name . " mg
            JOIN Genre g ON mg.genreId = g.id
            WHERE mg.movieId = :movieId
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function deleteByMovieId($movieId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE movieId = :movieId";
        $stmt = $this->conn->prepare($query);
        
        // Liên kết tham số
        $stmt->bindParam(":movieId", $movieId);

        return $stmt->execute();
    }
}
?>
