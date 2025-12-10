<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'artist') {
    header("Location: ../login.php");
    exit();
}

$uploader = $_SESSION['user_id'];

// --- UPLOAD SONG ---
if (isset($_POST['upload_song'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $artist = $conn->real_escape_string($_POST['artist']);
    $category_id = intval($_POST['category_id']);

    $songFile = $_FILES['audio_file'];
    $coverFile = $_FILES['cover_image'];

    $uploadDirMusic = "../assets/uploads/music/";
    $uploadDirImg = "../assets/uploads/thumbnails/";

    // Generate names
    $songName = time() . '_' . basename($songFile['name']);
    $targetMusic = $uploadDirMusic . $songName;

    $coverName = "default.jpg";
    if (!empty($coverFile['name'])) {
        $coverName = time() . '_' . basename($coverFile['name']);
        move_uploaded_file($coverFile['tmp_name'], $uploadDirImg . $coverName);
    }

    if (move_uploaded_file($songFile['tmp_name'], $targetMusic)) {
        // Insert with uploaded_by = Current Artist ID
        $stmt = $conn->prepare("INSERT INTO songs (title, artist, category_id, file_path, cover_path, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissi", $title, $artist, $category_id, $songName, $coverName, $uploader);
        $stmt->execute();
        
        header("Location: index.php?msg=Track uploaded");
    } else {
        echo "Upload failed. Check php.ini size limits.";
    }
}
// --- UPDATE SONG ---
if (isset($_POST['update_song'])) {
    $id = intval($_POST['song_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $artist = $conn->real_escape_string($_POST['artist']);
    $category_id = intval($_POST['category_id']);
    
    // SECURITY: Verify ownership again before updating
    $check = $conn->query("SELECT id FROM songs WHERE id='$id' AND uploaded_by='$uploader'");
    
    if($check->num_rows > 0) {
        // Update Text Info
        $conn->query("UPDATE songs SET title='$title', artist='$artist', category_id='$category_id' WHERE id='$id'");

        // Update Cover if new one uploaded
        if (!empty($_FILES['cover_image']['name'])) {
            $coverFile = $_FILES['cover_image'];
            $coverName = time() . '_' . basename($coverFile['name']);
            
            if(move_uploaded_file($coverFile['tmp_name'], "../assets/uploads/thumbnails/" . $coverName)) {
                $conn->query("UPDATE songs SET cover_path='$coverName' WHERE id='$id'");
            }
        }
        
        header("Location: index.php?msg=Track updated successfully");
    } else {
        die("Error: Permission denied.");
    }
}
// --- DELETE SONG ---
if (isset($_GET['delete_song'])) {
    $id = intval($_GET['delete_song']);

    // SECURITY: Verify the song belongs to THIS artist before deleting
    $check = $conn->query("SELECT * FROM songs WHERE id='$id' AND uploaded_by='$uploader'");

    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        
        // Delete files
        if(file_exists("../assets/uploads/music/" . $row['file_path'])) unlink("../assets/uploads/music/" . $row['file_path']);
        if($row['cover_path'] != 'default.jpg' && file_exists("../assets/uploads/thumbnails/" . $row['cover_path'])) unlink("../assets/uploads/thumbnails/" . $row['cover_path']);

        // Delete DB entry
        $conn->query("DELETE FROM songs WHERE id='$id'");
        header("Location: index.php?msg=Deleted");
    } else {
        die("Error: Permission denied. You do not own this song.");
    }
}
?>