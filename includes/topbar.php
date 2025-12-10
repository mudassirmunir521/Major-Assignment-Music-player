<div class="d-flex justify-content-between align-items-center mb-5">
    <div class="d-flex gap-3">
        <button onclick="history.back()" class="btn glass text-white rounded-circle"><i class="fas fa-chevron-left"></i></button>
        <button onclick="history.forward()" class="btn glass text-white rounded-circle"><i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="dropdown">
        <button class="btn glass dropdown-toggle rounded-pill text-white px-3 py-2 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
            <div class="bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: var(--gradient-1);">
                <i class="fas fa-user small"></i>
            </div>
            <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark glass border-0 shadow-lg mt-2">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a class="dropdown-item" href="admin/index.php"><i class="fas fa-shield-alt me-2"></i> Admin Panel</a></li>
            <?php endif; ?>
            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>
</div>