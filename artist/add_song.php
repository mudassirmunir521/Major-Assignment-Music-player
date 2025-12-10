<?php
session_start();
include('../config/db.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'artist') { header("Location: ../login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
            <h3 class="fw-bold text-white mb-5 px-3">Artist Studio</h3>
            <a href="index.php" class="text-secondary"><i class="fas fa-chart-pie me-3"></i> My Stats</a>
            <a href="add_song.php" class="active"><i class="fas fa-cloud-upload-alt me-3"></i> Upload Track</a>
            <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back to Player</a>
        </div>

        <div class="p-5 w-100" style="margin-left: 260px;">
            <h2 class="fw-bold mb-4">Release New Track</h2>
            
            <div class="glass p-5 rounded-4" style="max-width: 600px;">
                <form action="process.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">TRACK TITLE</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">ARTIST NAME</label>
                        <input type="text" name="artist" class="form-control" value="<?php echo $_SESSION['username']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">GENRE / CATEGORY</label>
                        <select name="category_id" class="form-select">
                            <?php
                            $cats = $conn->query("SELECT * FROM categories");
                            while($c = $cats->fetch_assoc()) {
                                echo "<option value='".$c['id']."'>".$c['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">AUDIO FILE (.MP3)</label>
                        <input type="file" name="audio_file" class="form-control" accept=".mp3" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 small fw-bold">COVER ART</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" name="upload_song" class="btn btn-primary-gradient w-100 py-3 rounded-pill fw-bold border-0 shadow">
                        PUBLISH TRACK
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>