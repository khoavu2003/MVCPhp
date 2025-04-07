<?php

class WatchlistMovies {
    private $conn;
    private $table_name = "WatchlistMovies";

    public $id;
    public $watchlistId;
    public $movieId;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả WatchlistMovies
    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy WatchlistMovies theo ID
    function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->watchlistId = $row['watchlistId'];
            $this->movieId = $row['movieId'];
            return $row;
        }
        return null;
    }

    // Tạo mới WatchlistMovies
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET watchlistId=:watchlistId, movieId=:movieId";
        $stmt = $this->conn->prepare($query);

        $this->watchlistId = htmlspecialchars(strip_tags($this->watchlistId));
        $this->movieId = htmlspecialchars(strip_tags($this->movieId));

        $stmt->bindParam(":watchlistId", $this->watchlistId);
        $stmt->bindParam(":movieId", $this->movieId);

        return $stmt->execute();
    }

    // Cập nhật WatchlistMovies
    function update() {
        $query = "UPDATE " . $this->table_name . " SET watchlistId=:watchlistId, movieId=:movieId WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->watchlistId = htmlspecialchars(strip_tags($this->watchlistId));
        $this->movieId = htmlspecialchars(strip_tags($this->movieId));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":watchlistId", $this->watchlistId);
        $stmt->bindParam(":movieId", $this->movieId);

        return $stmt->execute();
    }

    // Xóa WatchlistMovies
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
