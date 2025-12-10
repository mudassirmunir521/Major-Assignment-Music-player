<?php
session_start();
include('../config/db.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Add a subtle hover effect for clickable cards */
        .hover-card { transition: transform 0.3s ease, background 0.3s; }
        .hover-card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar glass border-0" style="width: 260px; height: 100vh; position: fixed;">
        <h3 class="fw-bold text-white mb-5 px-3">Admin Panel</h3>
        <a href="index.php" class="active"><i class="fas fa-chart-line me-3"></i> Dashboard</a>
        <a href="add_song.php"><i class="fas fa-cloud-upload-alt me-3"></i> Upload Song</a>
        <a href="../index.php" class="mt-auto text-secondary"><i class="fas fa-arrow-left me-3"></i> Back to App</a>
    </div>

    <div class="p-5 w-100" style="margin-left: 260px;">
        <h2 class="fw-bold mb-4">Dashboard Overview</h2>
        
        <div class="row mb-5 g-4">
            
            <div class="col-md-4">
                <div class="glass p-4 rounded-4 position-relative overflow-hidden h-100">
                    <h3>Total Songs</h3>
                    <h1 class="display-4 fw-bold">
                        <?php echo $conn->query("SELECT count(*) as c FROM songs")->fetch_assoc()['c']; ?>
                    </h1>
                    <i class="fas fa-music position-absolute" style="right: -10px; bottom: -10px; font-size: 100px; opacity: 0.1;"></i>
                </div>
            </div>

            <div class="col-md-4">
                <a href="manage_users.php" class="text-decoration-none text-white d-block h-100">
                    <div class="glass p-4 rounded-4 position-relative overflow-hidden h-100 hover-card">
                        <h3>Registered Users</h3>
                        <h1 class="display-4 fw-bold">
                            <?php echo $conn->query("SELECT count(*) as c FROM users")->fetch_assoc()['c']; ?>
                        </h1>
                        <i class="fas fa-users position-absolute" style="right: -10px; bottom: -10px; font-size: 100px; opacity: 0.1;"></i>
                        
                        <div class="position-absolute top-0 end-0 p-3 opacity-50">
                            <i class="fas fa-external-link-alt"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="glass p-4 rounded-4">
            <h4 class="mb-3">All Songs</h4>
            <table class="table table-glass align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM songs ORDER BY id DESC");
                    while ($row = $res->fetch_assoc()):
                        $cover = !empty($row['cover_path']) ? $row['cover_path'] : 'default.jpg';
                    ?>
                    <tr>
                        <td>
                            <img src="../assets/uploads/thumbnails/<?php echo $cover; ?>" width="40" height="40" class="rounded object-fit-cover">
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['artist']); ?></td>
                        <td class="text-end">
                            <a href="edit_song.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary rounded-pill me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <a href="process.php?delete_song=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-danger rounded-pill" 
                               onclick="return confirm('Are you sure you want to delete this song?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>