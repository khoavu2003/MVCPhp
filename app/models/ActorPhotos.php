<?php

class ActorPhotos {
    private $conn;
    private $table_name = "ActorPhotos";

    public $id;
    public $actorId;
    public $photoUrl;

    public function __construct($db) {
        $this->conn = $db;
    }

    function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->actorId = $row['actorId'];
            $this->photoUrl = $row['photoUrl'];
            return $row;
        }
        return null;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET actorId=:actorId, photoUrl=:photoUrl";
        $stmt = $this->conn->prepare($query);

        $this->actorId = htmlspecialchars(strip_tags($this->actorId));
        $this->photoUrl = htmlspecialchars(strip_tags($this->photoUrl));

        $stmt->bindParam(":actorId", $this->actorId);
        $stmt->bindParam(":photoUrl", $this->photoUrl);

        return $stmt->execute();
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET actorId=:actorId, photoUrl=:photoUrl 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->actorId = htmlspecialchars(strip_tags($this->actorId));
        $this->photoUrl = htmlspecialchars(strip_tags($this->photoUrl));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":actorId", $this->actorId);
        $stmt->bindParam(":photoUrl", $this->photoUrl);

        return $stmt->execute();
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
