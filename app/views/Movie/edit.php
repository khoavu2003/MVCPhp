<form action="/Movie_Project/Movie/update/<?php echo $movie['id']; ?>" method="POST">
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

    <!-- Trailer URL -->
    <div class="mb-3">
        <label for="trailer" class="form-label">Trailer URL</label>
        <input type="text" class="form-control" id="trailer" name="trailer" value="<?php echo $movie['trailer']; ?>" required>
    </div>

    <!-- Theatrical Release Date -->
    <div class="mb-3">
        <label for="theatricalReleaseDate" class="form-label">Theatrical Release Date</label>
        <input type="date" class="form-control" id="theatricalReleaseDate" name="theatricalReleaseDate" value="<?php echo $movie['theatricalReleaseDate']; ?>" required>
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
