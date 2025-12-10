<?php
session_start();
include('../config/db.php');

// Security: Only allow Artists
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'artist') { 
    header("Location: ../login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Artist Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="d-flex">
    <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
        <h3 class="fw-bold text-white mb-5 px-3">Artist Studio</h3>
        <a href="index.php" class="active"><i class="fas fa-chart-pie me-3"></i> My Stats</a>
        <a href="add_song.php" class="text-secondary"><i class="fas fa-cloud-upload-alt me-3"></i> Upload Track</a>
        <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back to Player</a>
    </div>

    <div class="p-5 w-100" style="margin-left: 260px;">
        <h2 class="fw-bold mb-4">Hello, <?php echo $_SESSION['username']; ?></h2>
        
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="glass p-4 rounded-4 position-relative overflow-hidden">
                    <h3>My Uploads</h3>
                    <h1 class="display-4 fw-bold">
                        <?php echo $conn->query("SELECT count(*) as c FROM songs WHERE uploaded_by='$user_id'")->fetch_assoc()['c']; ?>
                    </h1>
                    <i class="fas fa-microphone-lines position-absolute" style="right: -10px; bottom: -10px; font-size: 100px; opacity: 0.1;"></i>
                </div>
            </div>
        </div>

        <div class="glass p-4 rounded-4">
            <h4 class="mb-3">My Discography</h4>
            <table class="table table-glass align-middle">
                <thead>
                    <tr><th>Cover</th><th>Title</th><th>Date Added</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php
                    // Only fetch songs by THIS user
                    $res = $conn->query("SELECT * FROM songs WHERE uploaded_by='$user_id' ORDER BY id DESC");
                    
                    if($res->num_rows > 0):
                        while ($row = $res->fetch_assoc()):
                            $cover = !empty($row['cover_path']) ? $row['cover_path'] : 'default.jpg';
                    ?>
                    <tr>
                        <td><img src="../assets/uploads/thumbnails/<?php echo $cover; ?>" width="50" class="rounded shadow-sm"></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td class="text-white-50"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td class="text-end">
                            <a href="edit_song.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary rounded-pill me-2"><i class="fas fa-edit"></i></a>
                            
                            <a href="process.php?delete_song=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-danger rounded-pill" 
                               onclick="return confirm('Delete this track?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4">You haven't uploaded any songs yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>