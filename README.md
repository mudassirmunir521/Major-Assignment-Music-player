# 🎵 Aura Music Player

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Aura** is a modern, glassmorphism-styled music streaming platform built with **Core PHP** and **MySQL**. It acts as a community platform where Users can listen, Artists can release tracks, and Admins can manage the entire ecosystem.

---

## ✨ Key Features

### 🎧 User Experience (Listeners)
* **Immersive UI:** Dark mode with animated background orbs and glassmorphism effects.
* **Audio Player:** Sticky bottom player with Play/Pause, Next/Prev, Volume, and scrubbing.
* **Library Management:** Create custom playlists and manage them.
* **Like System:** One-click "Heart" button that saves songs to a "Liked Songs" playlist.
* **Profile Management:** Update username, email, and password securely.

### 🎤 Artist Studio (New!)
* **Dedicated Dashboard:** Artists have their own portal separate from Admins.
* **Upload & Manage:** Artists can upload MP3s, add cover art, and edit/delete *only* their own tracks.
* **Stats:** View upload counts and discography management.

### 🛡️ Admin Panel
* **Global Dashboard:** View total system stats (Songs, Users).
* **Content Control:** Delete any song or user (Moderation).
* **User Management:** View all registered users and their roles.

---

## 🚀 Installation & Setup

### 1. Prerequisites
* XAMPP / WAMP / MAMP (Apache & MySQL).
* A code editor (VS Code recommended).

### 2. Database Setup
1.  Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Create a database named `music_player`.
3.  Import the `music_db.sql` file located in the root directory.

### 3. Project Configuration
1.  Clone or unzip this project into `C:/xampp/htdocs/music-player/`.
2.  Open `config/db.php` and verify connection details:
    ```php
    $servername = "localhost";
    $username = "root";
    $password = ""; // Leave empty for default XAMPP
    $dbname = "music_player";
    ```

### 4. Create Required Folders
Ensure the following directories exist for uploads:
/assets/uploads/music//assets/uploads/thumbnails/*(Tip: Add a `default.jpg` image in the thumbnails folder).*

### 5. **CRITICAL: PHP Configuration**
To allow MP3 uploads, modify your `php.ini` file (Config button in XAMPP):
```ini
upload_max_filesize = 40M
post_max_size = 40M
Restart the Apache Server after saving.📂 Project StructurePlaintext/music-player/
│
├── admin/                 # Admin Dashboard (Global Control)
├── artist/                # Artist Studio (Upload/Edit own songs)
│
├── assets/
│   ├── css/               # Glassmorphism Stylesheet
│   ├── js/                # Audio Player Logic (AJAX/JS)
│   └── uploads/           # Storage for MP3s and Images
│
├── includes/              # Layout Partials (Sidebar, Player, etc.)
├── config/                # Database Connection
│
├── index.php              # Main Discovery Page
├── playlist.php           # Library & Liked Songs
├── profile.php            # Edit Profile Page
├── register.php           # Sign Up (User/Artist Selector)
├── login.php              # Authentication
└── ajax_like.php          # Backend logic for Likes
👤 Roles & CredentialsYou can create accounts via register.php.RoleCapabilitiesHow to get?UserListen, Like, Playlist, Profile EditSign up as "Listener"ArtistUpload Songs, Edit Own Songs, 
Artist StudioSign up as "Artist"
AdminDelete Any Song, Ban Users, Global StatsChange role to 'admin' in DB manually