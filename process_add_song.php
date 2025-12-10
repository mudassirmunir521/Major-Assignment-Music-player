<?php
session_start();
include('config/db.php');

// 1. Check if data exists
if(isset($_GET['playlist_id']) && isset($_GET['song_id']) && isset($_SESSION['user_id'])) {
    
    $pid = intval($_GET['playlist_id']);
    $sid = intval($_GET['song_id']);
    $uid = $_SESSION['user_id'];

    // 2. Security: Verify the playlist belongs to the logged-in user
    $check_owner = $conn->query("SELECT id FROM playlists WHERE id='$pid' AND user_id='$uid'");
    
    if($check_owner->num_rows > 0) {
        // 3. Check if song is already in the playlist
        $check_duplicate = $conn->query("SELECT * FROM playlist_songs WHERE playlist_id='$pid' AND song_id='$sid'");
        
        if($check_duplicate->num_rows == 0) {
            // 4. Insert
            $insert = $conn->query("INSERT INTO playlist_songs (playlist_id, song_id) VALUES ('$pid', '$sid')");
        }
    }
}

// 5. Redirect back to where they came from (or Home)
if(isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: index.php");
}
exit();
?>