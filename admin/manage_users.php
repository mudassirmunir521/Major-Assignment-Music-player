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
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="d-flex">
        
        <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
            <h3 class="fw-bold text-white mb-5 px-3">Admin Panel</h3>
            <a href="index.php" class="text-secondary"><i class="fas fa-chart-line me-3"></i> Dashboard</a>
            <a href="add_song.php" class="text-secondary"><i class="fas fa-cloud-upload-alt me-3"></i> Upload Song</a>
            <a href="manage_users.php" class="active"><i class="fas fa-users me-3"></i> Users</a>
            <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back to App</a>
        </div>

        <div class="p-5 w-100" style="margin-left: 260px;">
            <h2 class="fw-bold mb-4">Manage Users</h2>
            
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success border-0 bg-success text-white bg-opacity-75"><?php echo $_GET['msg']; ?></div>
            <?php endif; ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger border-0 bg-danger text-white bg-opacity-75"><?php echo $_GET['error']; ?></div>
            <?php endif; ?>

            <div class="glass p-4 rounded-4">
                <div class="table-responsive">
                    <table class="table table-glass align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Date Joined</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM users ORDER BY id DESC");
                            while ($row = $res->fetch_assoc()):
                                // Get first letter for avatar
                                $initial = strtoupper(substr($row['username'], 0, 1));
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow" 
                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <?php echo $initial; ?>
                                        </div>
                                        <span class="fw-bold"><?php echo htmlspecialchars($row['username']); ?></span>
                                    </div>
                                </td>
                                
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                
                                <td>
                                    <?php if($row['role'] == 'admin'): ?>
                                        <span class="badge rounded-pill bg-success bg-opacity-75 px-3 py-2">Admin</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary bg-opacity-50 px-3 py-2">User</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-white-50"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                
                                <td class="text-end">
                                    <?php if($row['id'] != $_SESSION['user_id']): ?>
                                        <a href="process.php?delete_user=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger rounded-pill px-3" 
                                           onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-25 text-primary border border-primary">You</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>