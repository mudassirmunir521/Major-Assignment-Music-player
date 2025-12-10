<?php
session_start();
include('config/db.php');

$error = "";

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 1. Get Role from Form & Sanitize
    $role_input = $_POST['role'];
    
    // SECURITY: Only allow 'user' or 'artist'. If they try to send 'admin', force it to 'user'.
    $allowed_roles = ['user', 'artist'];
    $role = in_array($role_input, $allowed_roles) ? $role_input : 'user';

    // 2. Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // 3. Check if Email Exists
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // 4. Hash Password & Insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Updated SQL to include 'role'
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Aura Music</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            background: #0f0f16;
            position: relative;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        /* Animated Background Orbs */
        .orb { 
            position: absolute; 
            border-radius: 50%; 
            filter: blur(80px); 
            opacity: 0.6; 
            animation: float 10s infinite ease-in-out; 
            z-index: 0;
        }
        .orb-1 { width: 300px; height: 300px; background: #667eea; top: 5%; left: 15%; }
        .orb-2 { width: 400px; height: 400px; background: #764ba2; bottom: 5%; right: 15%; animation-delay: -5s; }
        
        @keyframes float { 
            0%, 100% { transform: translateY(0); } 
            50% { transform: translateY(-40px); } 
        }
        
        /* Glass Card Styling */
        .register-card { 
            width: 100%;
            max-width: 450px; 
            z-index: 10; 
            padding: 40px; 
            border-radius: 20px; 
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: none;
            border-color: #667eea;
        }
        .form-control::placeholder { color: #aaa; }
        
        /* Fix dropdown options color in dark mode */
        .form-select option {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="register-card text-center">
        
        <div class="mb-4">
            <i class="fas fa-compact-disc fa-3x" style="color: #667eea;"></i>
            <h2 class="fw-bold text-white mt-2">Create Account</h2>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 bg-danger text-white bg-opacity-75 py-2"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="text-start">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Profile Name" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>

            <div class="mb-3">
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Select Account Type</option>
                    <option value="user">Listener (I just want to listen)</option>
                    <option value="artist">Artist (I want to upload music)</option>
                </select>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password (Min 6 chars)" required>
            </div>

            <div class="mb-4">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>

            <button type="submit" name="register" class="btn btn-primary-gradient w-100 py-3 rounded-pill fw-bold shadow border-0" 
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; transition: 0.3s;">
                SIGN UP
            </button>
        </form>
        
        <p class="mt-4 text-white-50">
            Already have an account? 
            <a href="login.php" class="text-white fw-bold text-decoration-none">Log In</a>
            <div class="mt-3">
    <a href="home.php" class="text-white-50 text-decoration-none small"><i class="fas fa-arrow-left"></i> Back to Home</a>
</div>
        </p>
    </div>

</body>
</html>