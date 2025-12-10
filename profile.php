<?php
session_start();
include('config/db.php');

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";
$error = "";

// 2. Handle Form Submission
if (isset($_POST['update_profile'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email is taken by another user
    $check_email = $conn->query("SELECT id FROM users WHERE email='$email' AND id != '$user_id'");
    
    if ($check_email->num_rows > 0) {
        $error = "That email is already in use by another account.";
    } else {
        // Base Query
        $sql = "UPDATE users SET username='$username', email='$email'";

        // Password Update Logic
        if (!empty($new_password)) {
            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 6) {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql .= ", password='$hashed'";
                } else {
                    $error = "Password must be at least 6 characters.";
                }
            } else {
                $error = "New passwords do not match.";
            }
        }

        // Execute Update if no errors
        if (empty($error)) {
            $sql .= " WHERE id='$user_id'";
            if ($conn->query($sql)) {
                $msg = "Profile updated successfully!";
                $_SESSION['username'] = $username; // Update session immediately
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
    }
}

// 3. Fetch Current User Data
$user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
?>

<?php include('includes/header.php'); ?>

<div class="wrapper">
    <?php include('includes/sidebar.php'); ?>

    <div class="main-content">
        <?php include('includes/topbar.php'); ?>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-lg me-3" 
                             style="width: 80px; height: 80px; font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <div>
                            <h2 class="fw-bold text-white mb-0">Edit Profile</h2>
                            <p class="text-white-50 mb-0">Update your personal details</p>
                        </div>
                    </div>

                    <?php if($msg): ?>
                        <div class="alert alert-success border-0 bg-success text-white bg-opacity-75 rounded-4 mb-4">
                            <i class="fas fa-check-circle me-2"></i> <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($error): ?>
                        <div class="alert alert-danger border-0 bg-danger text-white bg-opacity-75 rounded-4 mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="glass p-5 rounded-5">
                        <form method="POST">
                            
                            <div class="mb-4">
                                <label class="form-label text-uppercase text-white-50 small fw-bold letter-spacing-2">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text glass text-white border-0"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" class="form-control text-white" 
                                           value="<?php echo htmlspecialchars($user['username']); ?>" required
                                           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-uppercase text-white-50 small fw-bold letter-spacing-2">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text glass text-white border-0"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control text-white" 
                                           value="<?php echo htmlspecialchars($user['email']); ?>" required
                                           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                                </div>
                            </div>

                            <hr class="border-secondary opacity-25 my-4">
                            <h5 class="text-white mb-3 fw-bold">Change Password <small class="text-muted fw-normal fs-6">(Optional)</small></h5>

                            <div class="mb-3">
                                <label class="form-label text-white-50 small">New Password</label>
                                <input type="password" name="new_password" class="form-control text-white" 
                                       placeholder="Leave blank to keep current"
                                       style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-white-50 small">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control text-white" 
                                       placeholder="Repeat new password"
                                       style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            </div>

                            <div class="d-flex gap-3 mt-5">
                                <a href="index.php" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold">Cancel</a>
                                <button type="submit" name="update_profile" class="btn btn-primary-gradient rounded-pill px-5 py-2 fw-bold shadow">
                                    Save Changes
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/bottom-player.php'); ?>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>