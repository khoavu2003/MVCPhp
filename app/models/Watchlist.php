<?php

class Watchlist {
    private $conn;
    private $table_name = "Watchlist";

    public $id;
    public $name;
    public $description;
    public $userId;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả Watchlist
    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy Watchlist theo ID
    function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->userId = $row['userId'];
            return $row;
        }
        return null;
    }

    // Tạo mới Watchlist
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description, userId=:userId";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->userId = htmlspecialchars(strip_tags($this->userId));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":userId", $this->userId);

        return $stmt->execute();
    }

    // Cập nhật Watchlist
    function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, userId=:userId WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->userId = htmlspecialchars(strip_tags($this->userId));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":userId", $this->userId);

        return $stmt->execute();
    }

    // Xóa Watchlist
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
