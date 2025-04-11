<?php
class Actor
{
    private $conn;
    private $table_name = "Actor";

    public $id;
    public $name;
    public $birthDate;
    public $birthPlace;
    public $description;
    public $profileImage;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function getById()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->name = $row['name'];
            $this->birthDate = $row['birthDate'];
            $this->birthPlace = $row['birthPlace'];
            $this->description = $row['description'];
            $this->profileImage = $row['profileImage'];
            return $row;
        }
        return null;
    }

    // Phương thức để lấy tất cả diễn viên của một bộ phim
    public function getActorsByMovieId($movieId)
    {
        $query = "
            SELECT a.id, a.name, a.profileImage
            FROM " . $this->table_name . " a
            JOIN MovieActor ma ON ma.actorId = a.id
            WHERE ma.movieId = :movieId
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, birthDate=:birthDate, birthPlace=:birthPlace, description=:description, profileImage=:profileImage";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->birthDate = htmlspecialchars(strip_tags($this->birthDate));
        $this->birthPlace = htmlspecialchars(strip_tags($this->birthPlace));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->profileImage = htmlspecialchars(strip_tags($this->profileImage));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":birthDate", $this->birthDate);
        $stmt->bindParam(":birthPlace", $this->birthPlace);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":profileImage", $this->profileImage);

        return $stmt->execute();
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, birthDate=:birthDate, birthPlace=:birthPlace, description=:description, profileImage=:profileImage 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->birthDate = htmlspecialchars(strip_tags($this->birthDate));
        $this->birthPlace = htmlspecialchars(strip_tags($this->birthPlace));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->profileImage = htmlspecialchars(strip_tags($this->profileImage));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":birthDate", $this->birthDate);
        $stmt->bindParam(":birthPlace", $this->birthPlace);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":profileImage", $this->profileImage);

        return $stmt->execute();
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}