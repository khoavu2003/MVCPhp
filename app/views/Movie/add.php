<form action="/Movie_Project/Movie/add" method="POST">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <div class="mb-3">
        <label for="releaseYear" class="form-label">Release Year</label>
        <input type="number" class="form-control" id="releaseYear" name="releaseYear" required>
    </div>
    <div class="mb-3">
        <label for="director" class="form-label">Director</label>
        <input type="text" class="form-control" id="director" name="director" required>
    </div>
    <div class="mb-3">
        <label for="poster" class="form-label">Poster URL</label>
        <input type="text" class="form-control" id="poster" name="poster" required>
    </div>
    <div class="mb-3">
        <label for="bannerImage" class="form-label">Banner Image URL</label>
        <input type="text" class="form-control" id="bannerImage" name="bannerImage" required>
    </div>

    <!-- Trailer URL -->
    <div class="mb-3">
        <label for="trailer" class="form-label">Trailer URL</label>
        <input type="text" class="form-control" id="trailer" name="trailer" required>
    </div>

    <!-- Theatrical Release Date -->
    <div class="mb-3">
        <label for="theatricalReleaseDate" class="form-label">Theatrical Release Date</label>
        <input type="date" class="form-control" id="theatricalReleaseDate" name="theatricalReleaseDate" required>
    </div>

    <!-- Dropdown for Actors -->
    <div class="mb-3">
        <label class="form-label">Select Actors</label>
        <div class="form-check">
            <?php foreach ($actors as $actor): ?>
                <input class="form-check-input" type="checkbox" value="<?php echo $actor['id']; ?>" id="actor_<?php echo $actor['id']; ?>" name="actors[]">
                <label class="form-check-label" for="actor_<?php echo $actor['id']; ?>">
                    <?php echo $actor['name']; ?>
                </label><br>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Dropdown for Genres -->
    <div class="mb-3">
        <label class="form-label">Select Genres</label>
        <div class="form-check">
            <?php foreach ($genres as $genre): ?>
                <input class="form-check-input" type="checkbox" value="<?php echo $genre['id']; ?>" id="genre_<?php echo $genre['id']; ?>" name="genres[]">
                <label class="form-check-label" for="genre_<?php echo $genre['id']; ?>">
                    <?php echo $genre['name']; ?>
                </label><br>
            <?php endforeach; ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Add Movie</button>
</form>
