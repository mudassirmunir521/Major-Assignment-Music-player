<?php
session_start();
include('config/db.php');

if(!isset($_SESSION['user_id']) || !isset($_POST['song_id'])) {
    die("Error");
}

$user_id = $_SESSION['user_id'];
$song_id = intval($_POST['song_id']);

// 1. Check if 'Liked Songs' playlist exists for this user
$check_pl = $conn->query("SELECT id FROM playlists WHERE user_id='$user_id' AND name='Liked Songs'");

if($check_pl->num_rows == 0) {
    // Create it if it doesn't exist
    $conn->query("INSERT INTO playlists (user_id, name) VALUES ('$user_id', 'Liked Songs')");
    $playlist_id = $conn->insert_id;
} else {
    $row = $check_pl->fetch_assoc();
    $playlist_id = $row['id'];
}

// 2. Check if song is already in this playlist
$check_song = $conn->query("SELECT * FROM playlist_songs WHERE playlist_id='$playlist_id' AND song_id='$song_id'");

if($check_song->num_rows > 0) {
    // It's there -> Remove it (Unlike)
    $conn->query("DELETE FROM playlist_songs WHERE playlist_id='$playlist_id' AND song_id='$song_id'");
    echo "Unliked";
} else {
    // It's not there -> Add it (Like)
    $conn->query("INSERT INTO playlist_songs (playlist_id, song_id) VALUES ('$playlist_id', '$song_id')");
    echo "Liked";
}
?>