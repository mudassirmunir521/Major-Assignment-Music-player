// --- ADD THIS AT THE BOTTOM OF admin/process.php ---

if (isset($_POST['update_song'])) {
    $id = intval($_POST['song_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $artist = $conn->real_escape_string($_POST['artist']);

    // Update Text Data
    $conn->query("UPDATE songs SET title='$title', artist='$artist' WHERE id='$id'");

    // Handle New Cover Image Upload
    if (!empty($_FILES['cover_image']['name'])) {
        $coverFile = $_FILES['cover_image'];
        $coverName = time() . '_' . basename($coverFile['name']);
        $targetCoverPath = "../assets/uploads/thumbnails/" . $coverName;

        if(move_uploaded_file($coverFile['tmp_name'], $targetCoverPath)) {
            // Update DB
            $conn->query("UPDATE songs SET cover_path='$coverName' WHERE id='$id'");
        }
    }

    header("Location: index.php?msg=Song updated successfully");
    exit();
}