<?php
class Movie
{
    private $conn;
    private $table_name = "movie";

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

    // Lấy phim đã ra mắt tính đến ngày hiện tại
    public function getMoviesByReleaseDate($limit = 4)
    {
        $query = "
        SELECT m.*, 
               GROUP_CONCAT(DISTINCT g.name) AS genre_names, 
               GROUP_CONCAT(DISTINCT a.name) AS actor_names,
               (SELECT AVG(rating) FROM ratings r WHERE r.movieId = m.id) AS rating
        FROM " . $this->table_name . " m
        LEFT JOIN MovieGenre mg ON m.id = mg.movieId
        LEFT JOIN Genre g ON mg.genreId = g.id
        LEFT JOIN MovieActor ma ON m.id = ma.movieId
        LEFT JOIN Actor a ON ma.actorId = a.id
        WHERE m.theatricalReleaseDate IS NOT NULL
        AND m.theatricalReleaseDate <= CURDATE()
        GROUP BY m.id
        ORDER BY m.theatricalReleaseDate DESC
        LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // Các phương thức khác (giữ nguyên)
    public function getNewlyAddedMovies($limit = 6)
    {
        $query = "
        SELECT m.id, m.title, m.poster, m.bannerImage, m.releaseYear, m.trailer, 
               m.theatricalReleaseDate, m.director,
               (SELECT AVG(rating) FROM ratings r WHERE r.movieId = m.id) AS rating
        FROM " . $this->table_name . " m
        WHERE m.releaseYear > 0
        ORDER BY m.theatricalReleaseDate DESC
        LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    public function getMoviesWithDetails($limit, $offset)
    {
        $query = "
        SELECT m.*, 
               GROUP_CONCAT(DISTINCT g.name) AS genre_names, 
               GROUP_CONCAT(DISTINCT a.name) AS actor_names,
               (SELECT AVG(rating) FROM ratings r WHERE r.movieId = m.id) AS rating
        FROM " . $this->table_name . " m
        LEFT JOIN MovieGenre mg ON m.id = mg.movieId
        LEFT JOIN Genre g ON mg.genreId = g.id
        LEFT JOIN MovieActor ma ON m.id = ma.movieId
        LEFT JOIN Actor a ON ma.actorId = a.id
        GROUP BY m.id
        LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
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
            $this->title = isset($row['title']) ? $row['title'] : 'Untitled';
            $this->description = isset($row['description']) ? $row['description'] : '';
            $this->releaseYear = isset($row['releaseYear']) && $row['releaseYear'] > 0 ? $row['releaseYear'] : 'Unknown';
            $this->director = isset($row['director']) ? $row['director'] : '';
            $this->poster = isset($row['poster']) ? $row['poster'] : '';
            $this->trailer = isset($row['trailer']) ? $row['trailer'] : '';
            $this->theatricalReleaseDate = isset($row['theatricalReleaseDate']) ? $row['theatricalReleaseDate'] : '';
            $this->bannerImage = isset($row['bannerImage']) ? $row['bannerImage'] : '';

            $movieData = [
                'id' => $row['id'],
                'title' => $this->title,
                'description' => $this->description,
                'releaseYear' => $this->releaseYear,
                'director' => $this->director,
                'poster' => $this->poster,
                'trailer' => $this->trailer,
                'theatricalReleaseDate' => $this->theatricalReleaseDate,
                'bannerImage' => $this->bannerImage
            ];
            return $movieData;
        }
        return null;
    }

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
    
    public function rateMovie($movieId, $userId, $rating)
    {
        $query = "SELECT id FROM ratings WHERE movieId = :movieId AND userId = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $query = "UPDATE ratings SET rating = :rating, createdAt = NOW() 
                      WHERE movieId = :movieId AND userId = :userId";
        } else {
            $query = "INSERT INTO ratings (movieId, userId, rating, createdAt) 
                      VALUES (:movieId, :userId, :rating, NOW())";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":movieId", $movieId);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":rating", $rating);

        return $stmt->execute();
    }

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
        // Xóa các đánh giá liên quan trong bảng ratings
        $queryRatings = "DELETE FROM ratings WHERE movieId = :movieId";
        $stmtRatings = $this->conn->prepare($queryRatings);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmtRatings->bindParam(":movieId", $this->id);
        $stmtRatings->execute();

        // Xóa các bản ghi trong bảng MovieGenre
        $queryMovieGenre = "DELETE FROM MovieGenre WHERE movieId = :movieId";
        $stmtMovieGenre = $this->conn->prepare($queryMovieGenre);
        $stmtMovieGenre->bindParam(":movieId", $this->id);
        $stmtMovieGenre->execute();

        // Xóa các bản ghi trong bảng MovieActor
        $queryMovieActor = "DELETE FROM MovieActor WHERE movieId = :movieId";
        $stmtMovieActor = $this->conn->prepare($queryMovieActor);
        $stmtMovieActor->bindParam(":movieId", $this->id);
        $stmtMovieActor->execute();

        // Xóa các bản ghi trong bảng WatchlistMovies
        $queryWatchlistMovies = "DELETE FROM WatchlistMovies WHERE movieId = :movieId";
        $stmtWatchlistMovies = $this->conn->prepare($queryWatchlistMovies);
        $stmtWatchlistMovies->bindParam(":movieId", $this->id);
        $stmtWatchlistMovies->execute();

        // Sau đó xóa bộ phim trong bảng Movie
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    function getMoviesByPage($limit, $offset)
    {
        $query = "SELECT * FROM " . $this->table_name . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    function getTotalMovies()
    {
        $query = "SELECT COUNT(*) as total_movies FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_movies'];
    }

    public function getMoviesByActorId($actorId, $limit = null, $offset = null)
    {
        $query = "SELECT m.* FROM " . $this->table_name . " m
                  JOIN MovieActor ma ON m.id = ma.movieId
                  WHERE ma.actorId = :actorId
                  AND m.releaseYear > 0
                  ORDER BY m.releaseYear DESC";

        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }
        if ($offset !== null) {
            $query .= " OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':actorId', $actorId);

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset !== null) {
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>