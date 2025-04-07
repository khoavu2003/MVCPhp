<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Additional styling for the sidebar and main content */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            color: white;
            position: fixed;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #444;
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        .header {
            background-color: #222;
            color: white;
            padding: 10px;
        }

        .table th, .table td {
            text-align: center;
        }
    </style>
</head>

<form action="/Movie_Project/Movie/update/<?php echo $movie['id']; ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo $movie['title']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required><?php echo $movie['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label for="releaseYear" class="form-label">Release Year</label>
        <input type="number" class="form-control" id="releaseYear" name="releaseYear" value="<?php echo $movie['releaseYear']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="director" class="form-label">Director</label>
        <input type="text" class="form-control" id="director" name="director" value="<?php echo $movie['director']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="poster" class="form-label">Poster URL</label>
        <input type="text" class="form-control" id="poster" name="poster" value="<?php echo $movie['poster']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="bannerImage" class="form-label">Banner Image URL</label>
        <input type="text" class="form-control" id="bannerImage" name="bannerImage" value="<?php echo $movie['bannerImage']; ?>" required>
    </div>

    <!-- Select Actors -->
    <div class="mb-3">
        <label for="actors" class="form-label">Select Actors</label>
        <select class="form-control" id="actors" name="actors[]" multiple required>
            <?php foreach ($actors as $actor): ?>
                <option value="<?php echo $actor['id']; ?>" 
                    <?php echo in_array($actor['id'], $assignedActorIds) ? 'selected' : ''; ?>>
                    <?php echo $actor['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Select Genres -->
    <div class="mb-3">
        <label for="genres" class="form-label">Select Genres</label>
        <select class="form-control" id="genres" name="genres[]" multiple required>
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo $genre['id']; ?>" 
                    <?php echo in_array($genre['id'], $assignedGenreIds) ? 'selected' : ''; ?>>
                    <?php echo $genre['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Movie</button>
</form>
