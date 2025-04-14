<?php

class MovieRatings {
    private $conn;
    private $table_name = "MovieRatings";

    public $id;
    public $movieId;
    public $userId;
    public $rating;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả MovieRatings
    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy MovieRatings theo ID
    function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->movieId = $row['movieId'];
            $this->userId = $row['userId'];
            $this->rating = $row['rating'];
            return $row;
        }
        return null;
    }

    // Tạo mới MovieRating
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET movieId=:movieId, userId=:userId, rating=:rating";
        $stmt = $this->conn->prepare($query);

        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->rating = htmlspecialchars(strip_tags($this->rating));

        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":rating", $this->rating);

        return $stmt->execute();
    }

    // Cập nhật MovieRating
    function update() {
        $query = "UPDATE " . $this->table_name . " SET movieId=:movieId, userId=:userId, rating=:rating WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->rating = htmlspecialchars(strip_tags($this->rating));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":rating", $this->rating);

        return $stmt->execute();
    }

    // Xóa MovieRating
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
