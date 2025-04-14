<?php

class MovieComments {
    private $conn;
    private $table_name = "MovieComments";

    public $id;
    public $movieId;
    public $userId;
    public $comment;
    public $date;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả MovieComments
    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy MovieComments theo ID
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
            $this->comment = $row['comment'];
            $this->date = $row['date'];
            return $row;
        }
        return null;
    }

    // Tạo mới MovieComment
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET movieId=:movieId, userId=:userId, comment=:comment";
        $stmt = $this->conn->prepare($query);

        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }

    // Cập nhật MovieComment
    function update() {
        $query = "UPDATE " . $this->table_name . " SET movieId=:movieId, userId=:userId, comment=:comment WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->movieId = htmlspecialchars(strip_tags($this->movieId));
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":movieId", $this->movieId);
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }

    // Xóa MovieComment
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
