<?php

class MovieActor {
    private $conn;
    private $table_name = "MovieActor";

    public $id;
    public $movieId;
    public $actorId;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả MovieActor
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
            $this->actorId = $row['actorId'];
            return $row;
        }
        return null;
    }
    function deleteByMovieId($movieId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE movieId = :movieId";
        $stmt = $this->conn->prepare($query);

        // Liên kết tham số
        $stmt->bindParam(":movieId", $movieId);

        return $stmt->execute();
    }
    // Tạo mới MovieActor
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET movieId=:movieId, actorId=:actorId";
        $stmt = $this->conn->prepare($query);

        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->actorId = htmlspecialchars(strip_tags($this->actorId));

        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":actorId", $this->actorId);

        return $stmt->execute();
    }

    // Cập nhật MovieActor
    function update() {
        $query = "UPDATE " . $this->table_name . " SET movieId=:movieId, actorId=:actorId WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->actorId = htmlspecialchars(strip_tags($this->actorId));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":actorId", $this->actorId);

        return $stmt->execute();
    }
    function getActorsByMovieId($movieId) {
        $query = "
        SELECT a.id, a.name
        FROM " . $this->table_name . " ma
        JOIN Actor a ON ma.actorId = a.id
        WHERE ma.movieId = :movieId
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->execute();

        // Trả về tất cả diễn viên gắn với movieId
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa MovieActor
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
