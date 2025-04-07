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

    <!-- Dropdown for Actor with collapse -->
    <div class="mb-3">
        <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#actorsCollapse" aria-expanded="false" aria-controls="actorsCollapse">
            Select Actors
        </button>
        <div class="collapse" id="actorsCollapse">
            <select class="form-control mt-2" id="actors" name="actors[]" multiple required>
                <?php foreach ($actors as $actor): ?>
                    <option value="<?php echo $actor['id']; ?>"><?php echo $actor['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Dropdown for Genre with collapse -->
    <div class="mb-3">
        <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#genresCollapse" aria-expanded="false" aria-controls="genresCollapse">
            Select Genres
        </button>
        <div class="collapse" id="genresCollapse">
            <select class="form-control mt-2" id="genres" name="genres[]" multiple required>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?php echo $genre['id']; ?>"><?php echo $genre['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Add Movie</button>
</form>
