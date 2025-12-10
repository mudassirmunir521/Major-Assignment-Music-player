<?php
// includes/functions.php

/**
 * Check if user is logged in
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Check if user is Admin
 */
function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php"); // Bounce back to home
        exit();
    }
}

/**
 * Sanitize User Input
 */
function sanitize($conn, $input) {
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($input))));
}

/**
 * Get Cover Image or Default
 */
function getCover($path) {
    if (!empty($path) && file_exists('assets/uploads/thumbnails/' . $path)) {
        return 'assets/uploads/thumbnails/' . $path;
    }
    return 'assets/img/default.jpg'; // Make sure you have a default image
}

/**
 * Dump and Die (for debugging)
 */
function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}
?>