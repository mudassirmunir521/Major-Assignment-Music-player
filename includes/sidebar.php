<div class="sidebar">
    <div class="mb-5 d-flex align-items-center gap-2 px-2">
        <i class="fas fa-compact-disc fa-2x" style="color: #667eea;"></i>
        <h3 class="m-0 fw-bold" style="background: linear-gradient(to right, #fff, #aaa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Aura</h3>
    </div>

    <div class="d-flex flex-column gap-2">
        <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-home me-3"></i> Home
        </a>
        <a href="playlist.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'playlist.php' ? 'active' : ''; ?>">
            <i class="fas fa-list-music me-3"></i> My Library
        </a>
    </div>

    <div class="mt-4 px-2">
        <small class="text-uppercase text-secondary fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Playlists</small>
        <hr class="border-secondary opacity-25 my-2">
    </div>
    
    <div class="overflow-auto mt-2" style="max-height: 200px;">
        <a href="playlist.php" class="text-white opacity-75"><i class="fas fa-plus-circle me-2"></i> Create New</a>
    </div>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <div class="mt-auto">
            <a href="admin/index.php" class="text-danger glass mt-3 justify-content-center">
                <i class="fas fa-shield-alt me-2"></i> Admin Area
            </a>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'artist'): ?>
        <div class="mt-auto">
            <a href="artist/index.php" class="text-info glass mt-3 justify-content-center" 
               style="background: rgba(0, 210, 255, 0.1); border-color: rgba(0, 210, 255, 0.2);">
                <i class="fas fa-microphone-alt me-2"></i> Artist Studio
            </a>
        </div>
    <?php endif; ?>

</div>