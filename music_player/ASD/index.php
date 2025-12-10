<?php 
session_start();
include('config/db.php');

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: home.php"); // Send them to the new Landing Page
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch User's Playlists (for the "Add to Playlist" dropdowns)
$my_playlists = [];
$pl_query = $conn->query("SELECT id, name FROM playlists WHERE user_id = '$user_id'");
while($pl = $pl_query->fetch_assoc()) {
    $my_playlists[] = $pl;
}
?>

<?php include('includes/header.php'); ?>

<div class="wrapper">
    <?php include('includes/sidebar.php'); ?>

    <div class="main-content">
        <?php include('includes/topbar.php'); ?>

        <div class="p-5 rounded-5 mb-5 position-relative overflow-hidden text-white" 
             style="background: var(--gradient-1); box-shadow: 0 20px 50px rgba(102, 126, 234, 0.3);">
            
            <div class="position-relative z-1 d-flex flex-column justify-content-center h-100">
                <span class="text-uppercase fw-bold letter-spacing-2 mb-2" style="font-size: 0.8rem; opacity: 0.8;">Featured Playlist</span>
                <h1 class="display-4 fw-bold mb-2">Weekly Top Hits</h1>
                <p class="mb-4 opacity-75" style="max-width: 500px;">Curated just for you. The hottest tracks updated daily to keep your vibe going.</p>
                
                <div class="d-flex gap-3">
                    <button class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary shadow-sm" style="transition: transform 0.2s;">
                        <i class="fas fa-play me-2"></i> Play Now
                    </button>
                    <button class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold">
                        <i class="far fa-heart me-2"></i> Save
                    </button>
                </div>
            </div>

            <i class="fas fa-music position-absolute" 
               style="font-size: 250px; right: -30px; bottom: -60px; opacity: 0.15; transform: rotate(-15deg);"></i>
            <div class="position-absolute rounded-circle bg-white" 
                 style="width: 300px; height: 300px; top: -100px; left: -100px; opacity: 0.05; filter: blur(50px);"></div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold text-white m-0">Trending Songs</h4>
            <a href="#" class="text-secondary small text-decoration-none hover-white">Show All</a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
            
            <?php
            // 3. Fetch Songs
            $query = "SELECT * FROM songs ORDER BY id DESC LIMIT 20";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Prepare Data
                    $cover = !empty($row['cover_path']) ? $row['cover_path'] : 'default.jpg';
                    $file = htmlspecialchars($row['file_path']);
                    $title = htmlspecialchars($row['title'], ENT_QUOTES);
                    $artist = htmlspecialchars($row['artist'], ENT_QUOTES);
                    $song_id = $row['id'];
            ?>
            
            <div class="col">
                <div class="music-card h-100" 
                     onclick="playSong('<?php echo $file; ?>', '<?php echo $title; ?>', '<?php echo $artist; ?>', '<?php echo $cover; ?>', <?php echo $song_id; ?>)">
                    
                    <div class="position-relative mb-3">
                        <img src="assets/uploads/thumbnails/<?php echo $cover; ?>" alt="art" class="w-100 rounded-3 shadow-sm" style="aspect-ratio: 1/1; object-fit: cover;">
                        
                        <div class="play-overlay">
                            <button class="btn-play-card shadow-lg">
                                <i class="fas fa-play ps-1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-start">
                        <div class="overflow-hidden me-2">
                            <h6 class="text-white text-truncate mb-1 fw-bold"><?php echo $title; ?></h6>
                            <small class="text-secondary d-block text-truncate" style="font-size: 0.85rem;"><?php echo $artist; ?></small>
                        </div>
                        
                        <div class="dropdown" onclick="event.stopPropagation();">
                            <button class="btn btn-link text-secondary p-0 border-0 hover-white" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark glass border-0 shadow-lg">
                                <li><h6 class="dropdown-header text-uppercase small letter-spacing-2">Add to Playlist</h6></li>
                                
                                <?php if(empty($my_playlists)): ?>
                                    <li><span class="dropdown-item disabled text-muted">No playlists yet</span></li>
                                <?php else: ?>
                                    <?php foreach($my_playlists as $pl): ?>
                                        <li>
                                            <a class="dropdown-item" href="process_add_song.php?playlist_id=<?php echo $pl['id']; ?>&song_id=<?php echo $song_id; ?>">
                                                <i class="fas fa-music me-2 text-secondary"></i> <?php echo htmlspecialchars($pl['name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                <li>
                                    <a href="playlist.php" class="dropdown-item text-primary fw-bold">
                                        <i class="fas fa-plus-circle me-2"></i> Create New
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <?php 
                } // End While
            } else {
                echo "<div class='col-12'><div class='alert glass text-white'>No songs uploaded yet. Admin needs to upload music via the Admin Panel.</div></div>";
            }
            ?>
        </div> </div> </div> <?php include('includes/bottom-player.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>