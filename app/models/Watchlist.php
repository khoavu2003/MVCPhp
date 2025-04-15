<?php

class Watchlist
{
    private $conn;
    private $table_name = "Watchlist";

    public $id;
    public $name;
    public $description;
    public $userId;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả Watchlist của user
    function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE userId = :userId";
        $stmt = $this->conn->prepare($query);
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $stmt->bindParam(":userId", $this->userId);
        $stmt->execute();
        return $stmt;
    }

    // Lấy Watchlist theo ID
    function getById()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Ép kiểu id thành số nguyên
        $this->id = (int) $this->id;
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT); // Chỉ định kiểu dữ liệu là INT

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

    // Lấy hoặc tạo Default Watchlist cho user
    function getOrCreateDefault()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE userId = :userId AND name = 'Default Watchlist'";
        $stmt = $this->conn->prepare($query);
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $stmt->bindParam(":userId", $this->userId);
        $stmt->execute();

        $defaultWatchlist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($defaultWatchlist) {
            $this->id = $defaultWatchlist['id'];
            $this->name = $defaultWatchlist['name'];
            $this->description = $defaultWatchlist['description'];
            return $defaultWatchlist['id'];
        }

        $this->name = 'Default Watchlist';
        $this->description = 'Danh sách phim mặc định.';
        $this->create();
        return $this->conn->lastInsertId();
    }

    // Lấy danh sách phim trong Watchlist
    function getMovies()
    {
        // Lấy danh sách phim trong watchlist
        $query = "
        SELECT m.* 
        FROM Movie m
        JOIN WatchlistMovies wm ON m.id = wm.movieId
        WHERE wm.watchlistId = :watchlistId
    ";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":watchlistId", $this->id);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy genres cho từng phim
        foreach ($movies as &$movie) {
            $queryGenres = "
            SELECT g.* 
            FROM genre g 
            JOIN moviegenre mg ON g.id = mg.genreId 
            WHERE mg.movieid = :movieId
        ";
            $stmtGenres = $this->conn->prepare($queryGenres);
            $stmtGenres->bindParam(":movieId", $movie['id']);
            $stmtGenres->execute();
            $movie['genres'] = $stmtGenres->fetchAll(PDO::FETCH_ASSOC);
        }

        return $movies;
    }

    // Tạo mới Watchlist
    function create()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE userId = :userId AND name = :name";
        $stmt = $this->conn->prepare($query);
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description, userId=:userId";
        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":userId", $this->userId);

        return $stmt->execute();
    }

    // Cập nhật Watchlist
    function update()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE userId = :userId AND name = :name AND id != :id";
        $stmt = $this->conn->prepare($query);
        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":userId", $this->userId);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, userId=:userId WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":userId", $this->userId);

        return $stmt->execute();
    }

    // Xóa Watchlist
    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
