<?php

class Genre
{
    private $conn;
    private $table_name = "Genre";

    public $id;
    public $name;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all genres
    function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get genre by ID
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
            return $row;
        }
        return null;
    }

    // Create a new genre
    function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $this->name = htmlspecialchars(strip_tags($data['name']));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);

        return $stmt->execute();
    }

    // Update an existing genre
    function update($data)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Sanitize and bind the data
        $this->id = htmlspecialchars(strip_tags($data['id']));
        $this->name = htmlspecialchars(strip_tags($data['name']));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);

        return $stmt->execute();
    }

    // Delete a genre
    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Get genres by movie ID
    public function getGenresByMovieId($movieId)
    {
        $query = "
        SELECT g.id, g.name
        FROM Genre g
        JOIN MovieGenre mg ON mg.genreId = g.id
        WHERE mg.movieId = :movieId
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
