<?php
session_start();
include('../config/db.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

if(!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = intval($_GET['id']);
$song = $conn->query("SELECT * FROM songs WHERE id='$id'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Song</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="d-flex">
    <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
        <h3 class="fw-bold text-white mb-5 px-3">Admin Panel</h3>
        <a href="index.php"><i class="fas fa-chart-line me-3"></i> Dashboard</a>
        <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back</a>
    </div>

    <div class="p-5 w-100" style="margin-left: 260px;">
        <h2 class="fw-bold mb-4">Edit Song</h2>
        <div class="glass p-5 rounded-4" style="max-width: 600px;">
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Song Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($song['title']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Artist</label>
                    <input type="text" name="artist" class="form-control" value="<?php echo htmlspecialchars($song['artist']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Update Cover Image (Optional)</label>
                    <input type="file" name="cover_image" class="form-control">
                    <small class="text-white-50">Leave empty to keep current image.</small>
                </div>

                <button type="submit" name="update_song" class="btn btn-primary w-100 fw-bold mt-3">Update Song</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>