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


    function create($data)
    {
        // Prepare the SQL query to insert the movie
        $query = "INSERT INTO " . $this->table_name . " 
              SET name=:name, birthDate=:birthDate, birthPlace=:birthPlace, description=:description, profileImage=:profileImage";

        $stmt = $this->conn->prepare($query);

        // Clean the data to avoid SQL injection
        $this->name = htmlspecialchars(strip_tags($data['name']));
        $this->birthDate = htmlspecialchars(strip_tags($data['birthDate']));
        $this->birthPlace = htmlspecialchars(strip_tags($data['birthPlace']));
        $this->description = htmlspecialchars(strip_tags($data['description']));
        $this->profileImage = htmlspecialchars(strip_tags($data['profileImage']));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":birthDate", $this->birthDate);
        $stmt->bindParam(":birthPlace", $this->birthPlace);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":profileImage", $this->profileImage);

        // Execute the query and return the result
        return $stmt->execute();
    }
    public function update($data)
    {
        // Prepare the SQL query to update the actor data
        $query = "UPDATE " . $this->table_name . " 
              SET name=:name, birthDate=:birthDate, birthPlace=:birthPlace, description=:description, profileImage=:profileImage
              WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind the data
        $this->id = htmlspecialchars(strip_tags($data['id']));
        $this->name = htmlspecialchars(strip_tags($data['name']));
        $this->birthDate = htmlspecialchars(strip_tags($data['birthDate']));
        $this->birthPlace = htmlspecialchars(strip_tags($data['birthPlace']));
        $this->description = htmlspecialchars(strip_tags($data['description']));
        $this->profileImage = htmlspecialchars(strip_tags($data['profileImage']));

        // Bind the parameters
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":birthDate", $this->birthDate);
        $stmt->bindParam(":birthPlace", $this->birthPlace);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":profileImage", $this->profileImage);

        // Execute the query and return the result
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