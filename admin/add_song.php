<?php
session_start();
include('../config/db.php');

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Song</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        
        <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
            <h3 class="fw-bold text-white mb-5 px-3">Admin Panel</h3>
            <a href="index.php" class="text-secondary"><i class="fas fa-chart-line me-3"></i> Dashboard</a>
            <a href="add_song.php" class="active"><i class="fas fa-cloud-upload-alt me-3"></i> Upload Song</a>
            <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back to App</a>
        </div>

        <div class="p-5 w-100" style="margin-left: 260px;">
            <h2 class="fw-bold mb-4">Upload New Song</h2>
            
            <div class="glass p-5 rounded-4" style="max-width: 600px;">
                <form action="process.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label class="form-label text-white-50 fw-bold small">SONG TITLE</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Blinding Lights" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-white-50 fw-bold small">ARTIST NAME</label>
                        <input type="text" name="artist" class="form-control" placeholder="e.g. The Weeknd" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 fw-bold small">CATEGORY</label>
                        <select name="category_id" class="form-select">
                            <?php
                            $cats = $conn->query("SELECT * FROM categories");
                            if($cats->num_rows > 0) {
                                while($c = $cats->fetch_assoc()) {
                                    echo "<option value='".$c['id']."'>".$c['name']."</option>";
                                }
                            } else {
                                echo "<option value='0'>No Categories Found</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 fw-bold small">AUDIO FILE (.MP3)</label>
                        <input type="file" name="audio_file" class="form-control" accept=".mp3" required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label text-white-50 fw-bold small">COVER ART (OPTIONAL)</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" name="upload_song" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow border-0" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-cloud-upload-alt me-2"></i> UPLOAD TRACK
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>