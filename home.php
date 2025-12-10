<?php
session_start();
include('config/db.php');

// Fetch Dynamic Stats
$user_count = $conn->query("SELECT count(*) as c FROM users")->fetch_assoc()['c'];
$song_count = $conn->query("SELECT count(*) as c FROM songs")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Aura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Specific Styles for Landing Page */
        body { overflow-y: auto; } /* Allow scrolling on home page */
        
        .hero-section { min-height: 85vh; display: flex; align-items: center; position: relative; z-index: 1; }
        
        .orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.5; z-index: 0; }
        .orb-1 { width: 400px; height: 400px; background: #667eea; top: -100px; left: -100px; }
        .orb-2 { width: 500px; height: 500px; background: #764ba2; bottom: 0; right: -100px; }

        .glass-nav {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mock-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transform: rotate(-5deg);
            transition: transform 0.5s ease;
        }
        .mock-card:hover { transform: rotate(0deg) scale(1.02); }
        
        .stat-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <nav class="navbar navbar-expand-lg fixed-top glass-nav">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="#">
                <i class="fas fa-compact-disc fa-lg" style="color: #667eea;"></i> Aura
            </a>
            <div class="d-flex gap-3">
                <a href="login.php" class="btn btn-outline-light rounded-pill px-4">Log In</a>
                <a href="register.php" class="btn btn-primary-gradient rounded-pill px-4 border-0">Sign Up</a>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="text-uppercase text-info fw-bold letter-spacing-2">The Future of Streaming</span>
                    <h1 class="display-3 fw-bold text-white my-3">Feel the Music,<br>Not the Interface.</h1>
                    <p class="text-white-50 lead mb-4">
                        Aura is a next-generation music player built with glassmorphism aesthetics. 
                        Stream high-quality audio, create custom playlists, and connect with artists directly.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="register.php" class="btn btn-primary-gradient rounded-pill px-5 py-3 fw-bold shadow-lg border-0">Start Listening Free</a>
                        <a href="#about" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold">Read More</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mock-card p-4 mx-auto" style="max-width: 400px;">
                        <img src="assets/uploads/thumbnails/default.png" class="w-100 rounded-3 shadow mb-3" alt="Album Art">
                        <h4 class="text-white fw-bold">Blinding Lights</h4>
                        <p class="text-white-50">The Weeknd</p>
                        
                        <div class="progress mb-3" style="height: 4px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-white" role="progressbar" style="width: 65%;"></div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-4 text-white fs-4">
                            <i class="fas fa-step-backward"></i>
                            <i class="fas fa-pause-circle fa-lg"></i>
                            <i class="fas fa-step-forward"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 position-relative z-1">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="stat-box">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h2 class="display-4 fw-bold text-white"><?php echo $user_count; ?></h2>
                        <p class="text-white-50 text-uppercase letter-spacing-2">Registered Users</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-box">
                        <i class="fas fa-music fa-3x text-info mb-3"></i>
                        <h2 class="display-4 fw-bold text-white"><?php echo $song_count; ?></h2>
                        <p class="text-white-50 text-uppercase letter-spacing-2">Tracks Uploaded</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-5 position-relative z-1 mb-5">
        <div class="container">
            <div class="glass p-5 rounded-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold text-white mb-3">About Us</h2>
                        <p class="text-white-50">
                            Aura Music Player is a software engineering project developed by students of 
                            <strong>City University of Science and Information Technology, Peshawar</strong>.
                            Our goal was to architect a scalable, object-oriented web application that simplifies music distribution for independent artists.
                        </p>
                        <h5 class="text-white mt-4">The Team</h5>
                        <ul class="list-unstyled text-white-50 row mt-3">
                            <li class="col-md-6 mb-2"><i class="fas fa-check-circle text-success me-2"></i> Mudassir Munir (Manager)</li>
                            <li class="col-md-6 mb-2"><i class="fas fa-check-circle text-success me-2"></i> Muhammad Huzaifa (UI/UX)</li>
                            <li class="col-md-6 mb-2"><i class="fas fa-check-circle text-success me-2"></i> Muhammad Danyal (Audio Engine)</li>
                            <li class="col-md-6 mb-2"><i class="fas fa-check-circle text-success me-2"></i> Muhammad Abbas (Playlist Logic)</li>
                        </ul>
                    </div>
                    <div class="col-lg-4 text-center">
                        <i class="fas fa-laptop-code fa-10x text-white opacity-10"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 text-center border-top border-secondary border-opacity-25 position-relative z-1" style="background: rgba(0,0,0,0.3);">
        <p class="text-white-50 mb-0">&copy; 2025 Aura Music Player. All rights reserved.</p>
        <small class="text-muted">Designed for Software Construction & Development (Fall 2025)</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>