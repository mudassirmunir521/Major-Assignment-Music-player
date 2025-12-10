<?php
session_start();
include('../config/db.php');

// Security: Check Role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'artist') { 
    header("Location: ../login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// Check ID & Ownership
if(!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = intval($_GET['id']);

// IMPORTANT: Ensure the artist owns this song
$check = $conn->query("SELECT * FROM songs WHERE id='$id' AND uploaded_by='$user_id'");

if($check->num_rows == 0) {
    die("Error: You do not have permission to edit this song.");
}

$song = $check->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        
        <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
            <h3 class="fw-bold text-white mb-5 px-3">Artist Studio</h3>
            <a href="index.php" class="text-secondary"><i class="fas fa-chart-pie me-3"></i> My Stats</a>
            <a href="add_song.php" class="text-secondary"><i class="fas fa-cloud-upload-alt me-3"></i> Upload Track</a>
            <a href="#" class="active"><i class="fas fa-edit me-3"></i> Edit Track</a>
            <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back to Player</a>
        </div>

        <div class="p-5 w-100" style="margin-left: 260px;">
            <h2 class="fw-bold mb-4">Edit Track Details</h2>
            
            <div class="glass p-5 rounded-4" style="max-width: 600px;">
                <form action="process.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">TRACK TITLE</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($song['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">ARTIST NAME</label>
                        <input type="text" name="artist" class="form-control" value="<?php echo htmlspecialchars($song['artist']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">CATEGORY</label>
                        <select name="category_id" class="form-select">
                            <?php
                            $cats = $conn->query("SELECT * FROM categories");
                            while($c = $cats->fetch_assoc()) {
                                $selected = ($c['id'] == $song['category_id']) ? 'selected' : '';
                                echo "<option value='".$c['id']."' $selected>".$c['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 small fw-bold">UPDATE COVER ART (OPTIONAL)</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <small class="text-white-50">Current:</small>
                            <img src="../assets/uploads/thumbnails/<?php echo $song['cover_path']; ?>" width="50" class="rounded ms-2">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="index.php" class="btn btn-outline-light w-50 py-3 rounded-pill fw-bold">CANCEL</a>
                        <button type="submit" name="update_song" class="btn btn-primary-gradient w-50 py-3 rounded-pill fw-bold border-0 shadow">
                            SAVE CHANGES
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>