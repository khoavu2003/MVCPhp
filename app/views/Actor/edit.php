<form action="/Movie_Project/Actor/update/<?php echo $actor['id']; ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Actor Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $actor['name']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="birthDate" class="form-label">Birth Date</label>
        <input type="date" class="form-control" id="birthDate" name="birthDate" value="<?php echo $actor['birthDate']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="birthPlace" class="form-label">Birth Place</label>
        <input type="text" class="form-control" id="birthPlace" name="birthPlace" value="<?php echo $actor['birthPlace']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required><?php echo $actor['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label for="profileImage" class="form-label">Profile Image URL</label>
        <input type="text" class="form-control" id="profileImage" name="profileImage" value="<?php echo $actor['profileImage']; ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Actor</button>
</form>
