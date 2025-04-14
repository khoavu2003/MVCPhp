<?php
// Include necessary files
include_once 'app/config/database.php';
include_once 'app/models/Genre.php';
include_once 'app/middleware/AuthMiddleware.php';

class GenreController
{
    private $db;
    private $genre;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->genre = new Genre($this->db);
    }

    // Display all genres
    public function index()
    {
        $genres = $this->genre->getAll()->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/Genre/manage_genre.php';  // Create the view to display genres
    }
    public function manage()
    {
        AuthMiddleware::checkAdmin();
        $genres = $this->genre->getAll()->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/Genre/manage_genre.php';  // Create the view to display genres
    }

    // Add new genre
    public function add()
    {
        AuthMiddleware::checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data for the new genre
            $data = [
                'name' => $_POST['name']
            ];

            // Check if the genre was added successfully
            if ($this->genre->create($data)) {
                $_SESSION['success'] = 'Genre added successfully';
                header('Location: /Movie_Project/Genre');
                exit;
            } else {
                $_SESSION['error'] = 'Error occurred while adding genre';
            }
        }

        // Include the form view to add a new genre
        include 'app/views/Genre/add.php';
    }

    // Edit genre details
    public function update($genreId)
    {
        AuthMiddleware::checkAdmin();
        // Get genre data by ID
        $this->genre->id = $genreId;
        $genre = $this->genre->getById();  // Get genre data from the database

        if (!$genre) {
            $_SESSION['error'] = 'Genre does not exist.';
            header('Location: /Movie_Project/Genre');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data for updating genre
            $data = [
                'id' => $genreId,  // Ensure the ID is part of the data
                'name' => $_POST['name']
            ];

            // Check if the genre was successfully updated
            if ($this->genre->update($data)) {
                $_SESSION['success'] = 'Genre updated successfully';
                header('Location: /Movie_Project/Genre');
                exit;
            } else {
                $_SESSION['error'] = 'Error occurred while updating genre';
            }
        }

        // Include the form view to edit genre
        include 'app/views/Genre/edit.php';
    }

    // Delete genre
    public function delete($genreId)
    {
        AuthMiddleware::checkAdmin();
        $this->genre->id = $genreId;
        if ($this->genre->delete()) {
            $_SESSION['success'] = 'Genre deleted successfully';
            header('Location: /Movie_Project/Genre');
            exit;
        } else {
            $_SESSION['error'] = 'Error occurred while deleting genre';
        }
    }
}
?>
