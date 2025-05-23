<?php
// Include necessary files
include_once 'app/config/database.php';
include_once 'app/models/Actor.php';

class ActorController
{
    private $db;
    private $actor;

    public function __construct()
    {

        $database = new Database();
        $this->db = $database->getConnection();
        $this->actor = new Actor($this->db);
    }

    // Display all actors
    public function index()
    {

        $actors = $this->actor->getAll()->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/Actor/manage_actor.php';  // Create the view to display actors
    }
    public function manage()
    {
        AuthMiddleware::checkAdmin();
        $actors = $this->actor->getAll()->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/Actor/manage_actor.php';  // Create the view to display actors
    }
    // Add new actor
    public function add()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        AuthMiddleware::checkAdmin();
        // Collect form data for the new actor
        $data = [
            'name' => $_POST['name'],
            'birthDate' => $_POST['birthDate'],
            'birthPlace' => $_POST['birthPlace'],
            'description' => $_POST['description'],
            'profileImage' => $_POST['profileImage']
        ];

        var_dump($data); // Debug the data to ensure it's coming correctly

        // Check if the data exists, then call the create function to add the actor
        if ($this->actor->create($data)) {
            $_SESSION['success'] = 'Actor added successfully';
            header('Location: /Movie_Project/Actor');
            exit;
        } else {
            $_SESSION['error'] = 'Error occurred while adding actor';
        }
    }

    // Include the form view to add a new actor
    include 'app/views/Actor/add.php';
}
    // Edit actor details
    public function update($actorId)
{
    AuthMiddleware::checkAdmin();
    // Get actor data by ID
    $this->actor->id = $actorId;
    $actor = $this->actor->getById();  // Get actor data from the database

    if (!$actor) {
        $_SESSION['error'] = 'Actor does not exist.';
        header('Location: /Movie_Project/Actor');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect form data for updating actor
        $data = [
            'id' => $actorId,  // Ensure the ID is part of the data
            'name' => $_POST['name'],
            'birthDate' => $_POST['birthDate'],
            'birthPlace' => $_POST['birthPlace'],
            'description' => $_POST['description'],
            'profileImage' => $_POST['profileImage']
        ];

        // Check if the actor was successfully updated
        if ($this->actor->update($data)) {
            $_SESSION['success'] = 'Actor updated successfully';
            header('Location: /Movie_Project/Actor');
            exit;
        } else {
            $_SESSION['error'] = 'Error occurred while updating actor';
        }
    }

    // Include the form view to edit actor
    include 'app/views/Actor/edit.php';
}
    // Delete actor
    public function delete($actorId)
    {
        AuthMiddleware::checkAdmin();
        $this->actor->id = $actorId;
        if ($this->actor->delete()) {
            $_SESSION['success'] = 'Actor deleted successfully';
            header('Location: /Movie_Project/Actor');
            exit;
        } else {
            $_SESSION['error'] = 'Error occurred while deleting actor';
        }
    }
}
?>
