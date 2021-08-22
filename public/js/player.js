var player = videojs('video', {
    aspectRatio: "640:360",
    playbackRates: [1, 1.5, 2],
});

var channelId = document.getElementById('video').dataset.channelid;
var videoId   = document.getElementById('video').dataset.videoid;
var isPlayed  = false;
var isPaused  = true;
var isViewed  = false;
let timer     = null,
    totalTime = 0;

player.on("play", function () {
    isPlayed = true;
    isPaused = false;

    if (isPaused && (!isPlayed)){
        return false;
    }else {
        timer = window.setInterval(function() {
            totalTime += 1;
        }, 1000);
    }
})

player.on("pause", function () {
    isPlayed = false;
    isPaused = true;

    if (isPlayed && (!isPaused)){
        return false;
    }else {
        if (timer) clearInterval(timer);
    }
})

player.on('timeupdate', function () {
    var percentagePlayed = Math.ceil(((totalTime / player.duration()) * 100))
    if ((percentagePlayed > 15) && (!isViewed)){
        isViewed = true;
        axios.put('/channels/' + channelId + '/videos/' + videoId)
            .catch(function (error) {})
    }
});
