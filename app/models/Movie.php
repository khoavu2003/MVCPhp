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
            $this->releaseYear = isset($row['releaseYear']) && $row['releaseYear'] > 0 ? $row['releaseYear'] : 'Unknown'; // Handle invalid years
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
        // Chuẩn bị câu lệnh SQL
        $query = "INSERT INTO " . $this->table_name . " 
              SET title=:title, description=:description, releaseYear=:releaseYear, 
                  director=:director, poster=:poster, bannerImage=:bannerImage";

        $stmt = $this->conn->prepare($query);

        // Xử lý dữ liệu
        $this->title = htmlspecialchars(strip_tags($data['title']));
        $this->description = htmlspecialchars(strip_tags($data['description']));
        $this->releaseYear = htmlspecialchars(strip_tags($data['releaseYear']));
        $this->director = htmlspecialchars(strip_tags($data['director']));
        $this->poster = htmlspecialchars(strip_tags($data['poster']));
        $this->bannerImage = htmlspecialchars(strip_tags($data['bannerImage']));

        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":releaseYear", $this->releaseYear);
        $stmt->bindParam(":director", $this->director);
        $stmt->bindParam(":poster", $this->poster);
        $stmt->bindParam(":bannerImage", $this->bannerImage);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId(); // Lấy ID của movie vừa chèn
            return true;
        }

        return false;
    }


    // Cập nhật phim
    function update()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, description=:description, releaseYear=:releaseYear, 
                      director=:director, poster=:poster, trailer=:trailer, 
                      theatricalReleaseDate=:theatricalReleaseDate, bannerImage=:bannerImage 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->releaseYear = htmlspecialchars(strip_tags($this->releaseYear));
        $this->director = htmlspecialchars(strip_tags($this->director));
        $this->poster = htmlspecialchars(strip_tags($this->poster));
        $this->trailer = htmlspecialchars(strip_tags($this->trailer));
        $this->theatricalReleaseDate = htmlspecialchars(strip_tags($this->theatricalReleaseDate));
        $this->bannerImage = htmlspecialchars(strip_tags($this->bannerImage));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":releaseYear", $this->releaseYear);
        $stmt->bindParam(":director", $this->director);
        $stmt->bindParam(":poster", $this->poster);
        $stmt->bindParam(":trailer", $this->trailer);
        $stmt->bindParam(":theatricalReleaseDate", $this->theatricalReleaseDate);
        $stmt->bindParam(":bannerImage", $this->bannerImage);

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
    public function getMoviesByActorId($actorId, $limit = null, $offset = null) {
        $query = "SELECT m.* FROM Movie m
                  JOIN MovieActor ma ON m.id = ma.movieId
                  WHERE ma.actorId = :actorId
                  AND m.releaseYear > 0  -- Add this condition to exclude invalid years
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
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        }
        if ($offset !== null) {
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
