<?php

class Movie
{
    private $conn;
    private $table_name = "Movie";

    public $id;
    public $title;
    public $description;
    public $releaseYear;
    public $director;
    public $poster;
    public $trailer;
    public $theatricalReleaseDate;
    public $bannerImage;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả phim
    function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy phim theo ID
    function getById()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->releaseYear = $row['releaseYear'];
            $this->director = $row['director'];
            $this->poster = $row['poster'];
            $this->trailer = $row['trailer'];
            $this->theatricalReleaseDate = $row['theatricalReleaseDate'];
            $this->bannerImage = $row['bannerImage'];
            return $row;
        }
        return null;
    }

    // Lấy đánh giá trung bình của phim
    public function getAverageRating($movieId)
    {
        $query = "SELECT AVG(rating) as average, COUNT(*) as count 
                  FROM ratings 
                  WHERE movieId = :movieId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'average' => $row['average'] ? number_format($row['average'], 1) : 0.0,
            'count' => $row['count']
        ];
    }

    // Lấy đánh giá của người dùng hiện tại
    public function getUserRating($movieId, $userId)
    {
        $query = "SELECT rating 
                  FROM ratings 
                  WHERE movieId = :movieId AND userId = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['rating'] : 0;
    }
    
    // Lưu hoặc cập nhật đánh giá của người dùng
    public function rateMovie($movieId, $userId, $rating)
    {
        // Kiểm tra xem người dùng đã đánh giá phim này chưa
        $query = "SELECT id FROM ratings WHERE movieId = :movieId AND userId = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Nếu đã có đánh giá, cập nhật
            $query = "UPDATE ratings SET rating = :rating, createdAt = NOW() 
                      WHERE movieId = :movieId AND userId = :userId";
        } else {
            // Nếu chưa có, thêm mới
            $query = "INSERT INTO ratings (movieId, userId, rating, createdAt) 
                      VALUES (:movieId, :userId, :rating, NOW())";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":rating", $rating);

        return $stmt->execute();
    }

    // Tạo phim mới
    public function getMoviesWithDetails($limit, $offset)
    {
        $query = "
        SELECT m.*, 
               GROUP_CONCAT(DISTINCT g.name) AS genre_names, 
               GROUP_CONCAT(DISTINCT a.name) AS actor_names 
        FROM Movie m
        LEFT JOIN MovieGenre mg ON m.id = mg.movieId
        LEFT JOIN Genre g ON mg.genreId = g.id
        LEFT JOIN MovieActor ma ON m.id = ma.movieId
        LEFT JOIN Actor a ON ma.actorId = a.id
        GROUP BY m.id
        LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    // Tạo mới movie
    public function create($data)
{
    // Prepare the SQL query to insert the movie
    $query = "INSERT INTO " . $this->table_name . " 
              SET title=:title, description=:description, releaseYear=:releaseYear, 
                  director=:director, poster=:poster, bannerImage=:bannerImage, 
                  trailer=:trailer, theatricalReleaseDate=:theatricalReleaseDate";

    $stmt = $this->conn->prepare($query);

    // Sanitize the data
    $this->title = htmlspecialchars(strip_tags($data['title']));
    $this->description = htmlspecialchars(strip_tags($data['description']));
    $this->releaseYear = htmlspecialchars(strip_tags($data['releaseYear']));
    $this->director = htmlspecialchars(strip_tags($data['director']));
    $this->poster = htmlspecialchars(strip_tags($data['poster']));
    $this->bannerImage = htmlspecialchars(strip_tags($data['bannerImage']));
    $this->trailer = htmlspecialchars(strip_tags($data['trailer']));  // Sanitize trailer
    $this->theatricalReleaseDate = htmlspecialchars(strip_tags($data['theatricalReleaseDate']));  // Sanitize theatricalReleaseDate

    // Bind parameters
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":releaseYear", $this->releaseYear);
    $stmt->bindParam(":director", $this->director);
    $stmt->bindParam(":poster", $this->poster);
    $stmt->bindParam(":bannerImage", $this->bannerImage);
    $stmt->bindParam(":trailer", $this->trailer);  // Bind trailer
    $stmt->bindParam(":theatricalReleaseDate", $this->theatricalReleaseDate);  // Bind theatricalReleaseDate

    // Execute the query
    if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId(); // Get the ID of the newly inserted movie
        return true;
    }

    return false;
}



    // Cập nhật phim
    public function update($data)
{
    // Cập nhật thông tin movie
    $query = "UPDATE " . $this->table_name . " 
              SET title=:title, description=:description, releaseYear=:releaseYear, 
                  director=:director, poster=:poster, bannerImage=:bannerImage
              WHERE id=:id";

    $stmt = $this->conn->prepare($query);

    // Lấy các giá trị từ data
    $this->title = htmlspecialchars(strip_tags($data['title']));
    $this->description = htmlspecialchars(strip_tags($data['description']));
    $this->releaseYear = htmlspecialchars(strip_tags($data['releaseYear']));
    $this->director = htmlspecialchars(strip_tags($data['director']));
    $this->poster = htmlspecialchars(strip_tags($data['poster']));
    $this->bannerImage = htmlspecialchars(strip_tags($data['bannerImage']));
    
    // Bind parameters
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":releaseYear", $this->releaseYear);
    $stmt->bindParam(":director", $this->director);
    $stmt->bindParam(":poster", $this->poster);
    $stmt->bindParam(":bannerImage", $this->bannerImage);

    // Thực hiện câu lệnh SQL
    return $stmt->execute();
}


    // Xóa phim
    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
    function getMoviesByPage($limit, $offset)
    {
        $query = "SELECT * FROM " . $this->table_name . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    // Lấy tổng số phim trong cơ sở dữ liệu
    function getTotalMovies()
    {
        $query = "SELECT COUNT(*) as total_movies FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_movies'];
    }
}
