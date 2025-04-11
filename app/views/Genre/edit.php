<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Genre</h2>
        <form action="/Movie_Project/Genre/update/<?php echo $genre['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Genre Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $genre['name']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Genre</button>
        </form>
    </div>
</body>
</html>
