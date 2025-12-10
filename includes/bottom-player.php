<div class="bottom-player">
    <div class="d-flex align-items-center gap-3" style="width: 30%;">
        <img id="currentCover" src="" class="rounded-3 shadow-lg" style="width: 60px; height: 60px; object-fit: cover; opacity: 0; transition: opacity 0.5s ease;">
        
        <div class="d-flex flex-column justify-content-center overflow-hidden">
            <h6 id="currentTitle" class="text-white mb-0 fw-bold text-truncate" style="font-size: 0.95rem; letter-spacing: 0.5px;">Select a song</h6>
            <small id="currentArtist" class="text-white-50 text-truncate" style="font-size: 0.8rem;">Ready to play</small>
        </div>

        <button id="likeBtn" class="btn btn-link p-0 border-0" onclick="toggleLike()" style="transition: transform 0.2s;">
            <i id="likeIcon" class="far fa-heart text-white-50 hover-white" style="font-size: 1.2rem;"></i>
        </button>
    </div>

    <div class="d-flex flex-column align-items-center justify-content-center" style="width: 40%;">
        
        <div class="d-flex align-items-center gap-4 mb-2">
            <i class="fas fa-random text-secondary small cursor-pointer hover-white" title="Shuffle"></i>
            <i class="fas fa-step-backward text-white cursor-pointer hover-scale" style="font-size: 1.2rem;"></i>
            
            <button id="playBtn" class="btn btn-white rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: 45px; height: 45px; background: white; border: none; box-shadow: 0 0 20px rgba(255, 255, 255, 0.4); transition: transform 0.2s;">
                <i id="playIcon" class="fas fa-play text-black" style="margin-left: 2px;"></i>
            </button>
            
            <i class="fas fa-step-forward text-white cursor-pointer hover-scale" style="font-size: 1.2rem;"></i>
            <i class="fas fa-redo text-secondary small cursor-pointer hover-white" title="Repeat"></i>
        </div>

        <div class="d-flex align-items-center w-100 gap-3">
            <span id="currentTime" class="text-white-50 small" style="font-family: monospace; font-size: 11px; min-width: 35px; text-align: right;">0:00</span>
            
            <div class="flex-grow-1 position-relative d-flex align-items-center">
                <input type="range" id="songProgress" class="form-range custom-range" value="0" style="height: 4px; cursor: pointer;">
            </div>
            
            <span id="totalDuration" class="text-white-50 small" style="font-family: monospace; font-size: 11px; min-width: 35px;">0:00</span>
        </div>
        
        <audio id="audioPlayer"></audio>
    </div>

    <div class="d-flex align-items-center justify-content-end gap-3" style="width: 30%;">
        <i class="fas fa-list text-white-50 cursor-pointer hover-white"></i>
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-volume-up text-white-50"></i>
            <input type="range" id="volumeBar" class="form-range custom-range" style="width: 100px; height: 4px;">
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.1); color: #fff; }
    .hover-white:hover { color: #fff !important; }
    
    /* Custom Range Slider Styling */
    .custom-range::-webkit-slider-thumb {
        background: #fff;
        box-shadow: 0 0 10px rgba(255,255,255,0.8);
        transform: scale(1);
        transition: transform 0.2s;
    }
    .custom-range:hover::-webkit-slider-thumb {
        transform: scale(1.3);
    }
</style>