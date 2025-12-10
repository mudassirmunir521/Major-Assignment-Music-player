const audioPlayer = document.getElementById('audioPlayer');
const playBtn = document.getElementById('playBtn');
const playIcon = document.getElementById('playIcon');
const progressBar = document.getElementById('songProgress');
const volumeBar = document.getElementById('volumeBar');

// Track Elements
const currentTitle = document.getElementById('currentTitle');
const currentArtist = document.getElementById('currentArtist');
const currentCover = document.getElementById('currentCover');
const currentTimeSpan = document.getElementById('currentTime');
const totalDurationSpan = document.getElementById('totalDuration');
const likeIcon = document.getElementById('likeIcon');

let isPlaying = false;
let currentSongId = null; // We need to track the ID for the Like system

// 1. Play Song Function
function playSong(songFile, title, artist, coverPath, songId) {
    // Update UI
    currentTitle.innerText = title;
    currentArtist.innerText = artist;
    currentSongId = songId; // Store ID for liking
    
    if(coverPath && coverPath !== '') {
        currentCover.src = "assets/uploads/thumbnails/" + coverPath;
        currentCover.style.opacity = "1";
    }

    // Reset Like Icon (Visual only - ideally check DB here)
    likeIcon.className = "far fa-heart text-white-50 hover-white"; 

    // Load Audio
    audioPlayer.src = "assets/uploads/music/" + songFile;
    
    // Wait for metadata to load to get duration
    audioPlayer.onloadedmetadata = function() {
        totalDurationSpan.innerText = formatTime(audioPlayer.duration);
    };

    audioPlayer.play();
    isPlaying = true;
    updatePlayIcon();
}

// 2. Play/Pause Toggle
playBtn.addEventListener('click', () => {
    if(!audioPlayer.src) return;
    
    if (isPlaying) {
        audioPlayer.pause();
        isPlaying = false;
    } else {
        audioPlayer.play();
        isPlaying = true;
    }
    updatePlayIcon();
});

function updatePlayIcon() {
    if (isPlaying) {
        playIcon.classList.remove('fa-play');
        playIcon.classList.add('fa-pause');
    } else {
        playIcon.classList.remove('fa-pause');
        playIcon.classList.add('fa-play');
    }
}

// 3. Update Progress Bar & Time
audioPlayer.addEventListener('timeupdate', (e) => {
    const { duration, currentTime } = e.srcElement;
    if(isNaN(duration)) return;

    // Update Bar
    const progressPercent = (currentTime / duration) * 100;
    progressBar.value = progressPercent;
    
    // Gradient Effect on Bar
    progressBar.style.background = `linear-gradient(to right, #ffffff 0%, #ffffff ${progressPercent}%, #444 ${progressPercent}%, #444 100%)`;

    // Update Time Numbers
    currentTimeSpan.innerText = formatTime(currentTime);
});

// 4. Seek Functionality
progressBar.addEventListener('input', () => {
    const duration = audioPlayer.duration;
    audioPlayer.currentTime = (progressBar.value / 100) * duration;
});

// 5. Volume Control
volumeBar.addEventListener('input', (e) => {
    audioPlayer.volume = e.target.value / 100;
    volumeBar.style.background = `linear-gradient(to right, #ffffff 0%, #ffffff ${e.target.value}%, #444 ${e.target.value}%, #444 100%)`;
});

// 6. Format Time Helper (Seconds -> MM:SS)
function formatTime(seconds) {
    const min = Math.floor(seconds / 60);
    const sec = Math.floor(seconds % 60);
    return `${min}:${sec < 10 ? '0' + sec : sec}`;
}

// 7. Like Button Logic (AJAX)
function toggleLike() {
    if(!currentSongId) return;

    // Visual Toggle immediately for UX
    if(likeIcon.classList.contains('far')) {
        likeIcon.className = "fas fa-heart text-danger"; // Filled Red Heart
        likeIcon.style.filter = "drop-shadow(0 0 5px #ff0000)";
    } else {
        likeIcon.className = "far fa-heart text-white-50 hover-white"; // Empty Heart
        likeIcon.style.filter = "none";
    }

    // Send to Backend
    fetch('ajax_like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'song_id=' + currentSongId
    })
    .then(response => response.text())
    .then(data => {
        console.log("Like Status:", data);
    });
}