<?php
session_start();
include('config/db.php');

// 1. If already logged in, redirect immediately
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$error = "";

// 2. Handle Login Form Submission
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check Database for Email
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // 3. Verify the Hashed Password
        if (password_verify($password, $user['password'])) {
            // Set Session Variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // 4. Redirect based on Role
            if ($user['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Aura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { 
            display: flex; align-items: center; justify-content: center; min-height: 100vh; 
            background: #0f0f16;
            position: relative;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }
        /* Animated Background Orbs */
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; animation: float 10s infinite; }
        .orb-1 { width: 300px; height: 300px; background: #667eea; top: 10%; left: 20%; }
        .orb-2 { width: 400px; height: 400px; background: #764ba2; bottom: 10%; right: 20%; animation-delay: -5s; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-40px); } }
        
        .login-card { 
            width: 100%;
            max-width: 400px; 
            z-index: 10; 
            padding: 40px; 
            border-radius: 20px; 
            /* Enhanced Glass Effect */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: none;
            border-color: #667eea;
        }
        
        /* THIS FIXES THE INVISIBLE PLACEHOLDER TEXT */
        .form-control::placeholder { 
            color: #aaa; 
            opacity: 1; /* Firefox fix */
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: 0.3s;
        }
        .btn-primary-gradient:hover {
            transform: scale(1.02);
            filter: brightness(1.1);
            color: white;
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-card text-center">
        <div class="mb-4">
            <i class="fas fa-compact-disc fa-3x" style="color: #667eea;"></i>
            <h2 class="fw-bold text-white mt-2">Welcome Back</h2>
        </div>

        <?php if(isset($_GET['registered'])): ?>
            <div class="alert alert-success border-0 bg-success text-white bg-opacity-75 py-2">Account created! Please log in.</div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 bg-danger text-white bg-opacity-75 py-2"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control py-3" placeholder="Email Address" required>
            </div>
            <div class="mb-4 text-start">
                <input type="password" name="password" class="form-control py-3" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary-gradient w-100 py-3 rounded-pill fw-bold shadow">LOG IN</button>
        </form>
        
        <p class="mt-4 text-white-50">Don't have an account? <a href="register.php" class="text-white fw-bold text-decoration-none">Sign Up</a></p>
        <div class="mt-3">
    <a href="home.php" class="text-white-50 text-decoration-none small"><i class="fas fa-arrow-left"></i> Back to Home</a>
</div>
    </div>
</body>
</html>