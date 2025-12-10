<?php
session_start();
include('config/db.php');

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$view = isset($_GET['id']) ? 'single' : 'list';

// --- HANDLE ACTIONS ---

// Create Playlist
if (isset($_POST['create_playlist'])) {
    $name = $conn->real_escape_string($_POST['playlist_name']);
    $conn->query("INSERT INTO playlists (user_id, name) VALUES ('$user_id', '$name')");
    header("Location: playlist.php");
    exit();
}

// Delete Playlist
if (isset($_GET['delete_playlist'])) {
    $pid = intval($_GET['delete_playlist']);
    $conn->query("DELETE FROM playlists WHERE id='$pid' AND user_id='$user_id'");
    header("Location: playlist.php");
    exit();
}

// Remove Song from Playlist
if (isset($_GET['remove_song']) && isset($_GET['id'])) {
    $pid = intval($_GET['id']);
    $sid = intval($_GET['remove_song']);
    
    // Check ownership
    $check = $conn->query("SELECT id FROM playlists WHERE id='$pid' AND user_id='$user_id'");
    if($check->num_rows > 0){
        $conn->query("DELETE FROM playlist_songs WHERE playlist_id='$pid' AND song_id='$sid'");
    }
    header("Location: playlist.php?id=$pid");
    exit();
}

// --- DATA FETCHING ---

// Check for "Liked Songs" playlist
$liked_playlist = $conn->query("SELECT id FROM playlists WHERE user_id='$user_id' AND name='Liked Songs'")->fetch_assoc();

$liked_count = 0;
$liked_id = 0;

if($liked_playlist) {
    $liked_id = $liked_playlist['id'];
    $count_q = $conn->query("SELECT count(*) as total FROM playlist_songs WHERE playlist_id='$liked_id'");
    $liked_count = $count_q->fetch_assoc()['total'];
}
?>

<?php include('includes/header.php'); ?>

<div class="wrapper">
    <?php include('includes/sidebar.php'); ?>

    <div class="main-content">
        <?php include('includes/topbar.php'); ?>

        <?php if($view == 'list'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-white">Your Library</h2>
                <button class="btn btn-primary-gradient rounded-pill px-4 fw-bold shadow" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i> New Playlist
                </button>
            </div>
            
            <div class="row g-4">
                
                <div class="col-md-3">
                    <?php if($liked_id > 0): ?>
                    <a href="playlist.php?id=<?php echo $liked_id; ?>" class="text-decoration-none">
                        <div class="music-card h-100 p-4 d-flex flex-column justify-content-end position-relative overflow-hidden" 
                             style="background: linear-gradient(135deg, #450af5, #8e8ee5); min-height: 220px;">
                            <div class="position-absolute" style="top: 20px; left: 20px;">
                                <i class="fas fa-heart text-white fa-2x"></i>
                            </div>
                            <h3 class="fw-bold text-white mb-1">Liked Songs</h3>
                            <p class="text-white opacity-75 m-0 fw-bold"><?php echo $liked_count; ?> Songs</p>
                        </div>
                    </a>
                    <?php else: ?>
                    <div class="music-card h-100 p-4 d-flex flex-column justify-content-end position-relative" 
                         style="background: linear-gradient(135deg, #333, #444); min-height: 220px; opacity: 0.7;">
                        <div class="position-absolute" style="top: 20px; left: 20px;">
                            <i class="far fa-heart text-white fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">Liked Songs</h3>
                        <p class="text-white opacity-75 m-0">No likes yet</p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php 
                // Fetch other playlists
                $res = $conn->query("SELECT * FROM playlists WHERE user_id = '$user_id' AND name != 'Liked Songs' ORDER BY id DESC");
                while($row = $res->fetch_assoc()): 
                    $pid_loop = $row['id'];
                    $c_res = $conn->query("SELECT count(*) as t FROM playlist_songs WHERE playlist_id='$pid_loop'")->fetch_assoc();
                    $song_count = $c_res['t'];
                ?>
                <div class="col-md-3">
                    <div class="music-card position-relative text-center p-4 h-100 d-flex flex-column align-items-center justify-content-center">
                        
                        <a href="playlist.php?delete_playlist=<?php echo $row['id']; ?>" 
                           class="position-absolute top-0 end-0 m-3 text-secondary hover-white"
                           onclick="return confirm('Delete this playlist permanently?')">
                           <i class="fas fa-times"></i>
                        </a>

                        <a href="playlist.php?id=<?php echo $row['id']; ?>" class="text-decoration-none w-100">
                            <div class="rounded shadow-lg d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                 style="width: 120px; height: 120px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                                <i class="fas fa-music fa-3x text-secondary"></i>
                            </div>
                            <h5 class="fw-bold text-white mb-1 text-truncate"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <small class="text-secondary"><?php echo $song_count; ?> Songs</small>
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

        <?php else: 
            $pid = intval($_GET['id']);
            $playlist = $conn->query("SELECT * FROM playlists WHERE id='$pid' AND user_id='$user_id'")->fetch_assoc();
            
            if(!$playlist) {
                echo "<div class='alert alert-danger'>Playlist not found. <a href='playlist.php'>Go Back</a></div>";
                exit;
            }
        ?>
            <div class="d-flex align-items-end gap-4 mb-5 p-4 rounded-4 position-relative overflow-hidden" 
                 style="background: linear-gradient(to bottom, rgba(102, 126, 234, 0.4), transparent);">
                
                <i class="fas fa-compact-disc position-absolute" style="font-size: 300px; right: -50px; bottom: -80px; opacity: 0.1;"></i>

                <div class="shadow-lg rounded d-flex align-items-center justify-content-center bg-dark position-relative z-1" 
                     style="width: 180px; height: 180px; min-width: 180px;">
                    <?php if($playlist['name'] == 'Liked Songs'): ?>
                        <i class="fas fa-heart fa-4x" style="color: #667eea;"></i>
                    <?php else: ?>
                        <i class="fas fa-music fa-4x text-muted"></i>
                    <?php endif; ?>
                </div>
                
                <div class="position-relative z-1">
                    <small class="text-uppercase fw-bold text-white-50 letter-spacing-2">Playlist</small>
                    <h1 class="display-3 fw-bold my-2 text-white"><?php echo htmlspecialchars($playlist['name']); ?></h1>
                    <p class="text-white-50 mb-0">Created by <?php echo $_SESSION['username']; ?></p>
                </div>
            </div>

            <div class="glass rounded-4 p-3">
                <table class="table table-glass mb-0">
                    <thead>
                        <tr class="text-uppercase text-secondary small letter-spacing-2">
                            <th style="width: 50px;">#</th>
                            <th>Title</th>
                            <th>Artist</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // FIX: Removed the incorrect 'ORDER BY ps.id' which caused the crash
                        $s_res = $conn->query("SELECT s.* FROM songs s JOIN playlist_songs ps ON s.id=ps.song_id WHERE ps.playlist_id='$pid'");
                        
                        if($s_res && $s_res->num_rows > 0):
                            $i=1;
                            while($s = $s_res->fetch_assoc()): 
                                $cover = !empty($s['cover_path']) ? $s['cover_path'] : 'default.jpg';
                                $js_title = addslashes($s['title']);
                                $js_artist = addslashes($s['artist']);
                        ?>
                        <tr class="align-middle song-row">
                            <td class="text-secondary"><?php echo $i++; ?></td>
                            
                            <td style="cursor: pointer;" 
                                onclick="playSong('<?php echo $s['file_path']; ?>', '<?php echo $js_title; ?>', '<?php echo $js_artist; ?>', '<?php echo $cover; ?>', <?php echo $s['id']; ?>)">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="assets/uploads/thumbnails/<?php echo $cover; ?>" class="rounded" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-white"><?php echo htmlspecialchars($s['title']); ?></div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="text-white-50"><?php echo htmlspecialchars($s['artist']); ?></td>
                            
                            <td class="text-end">
                                <a href="playlist.php?id=<?php echo $pid; ?>&remove_song=<?php echo $s['id']; ?>" 
                                   class="btn btn-link text-secondary hover-danger p-0">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No songs in this playlist yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="playlist.php" class="btn btn-outline-light rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to Library
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass text-white border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Create New Playlist</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="text" name="playlist_name" class="form-control" placeholder="My Playlist Name" required 
                           style="background: rgba(255,255,255,0.1); border: none; color: white;">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-white text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="create_playlist" class="btn btn-primary-gradient rounded-pill px-4">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/bottom-player.php'); ?>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>