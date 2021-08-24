var channelId  = document.getElementById('video').dataset.channelid;
var videoId    = document.getElementById('video').dataset.videoid;
var player = videojs('video', {
    aspectRatio: "640:360",
    playbackRates: [1, 1.5, 2]
});

player.ready(function() {
    this.hotkeys({
        volumeStep: 0.1,
        seekStep: 5,
        enableModifiersForNumbers: false
    });
});

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

player.theaterMode({ elementToToggle: 'videoTheater'});

player.on('theaterMode', function(elm, data) {
    var theaterBackground = $('.theater-background');
    var emimmTubeNav = $('#emimmTubeNav');
    var videoContainer = $('#videoContainer')
    if (data.theaterModeIsOn) {
        emimmTubeNav.removeClass('fixed-top', true);
        theaterBackground.removeAttr('style', true);
        theaterBackground.attr('style', 'opacity: 1; pointer-events: all;');
        videoContainer.removeAttr('style', true);
        videoContainer.attr('style', 'margin-top: -40px; margin-left: auto; margin-right: auto;')
    } else {
        emimmTubeNav.addClass('fixed-top', true);
        theaterBackground.removeAttr('style', true)
        theaterBackground.attr('style', 'opacity: 0; pointer-events: none;')
        videoContainer.removeAttr('style', true);
    }
});

player.hlsQualitySelector({
    displayCurrentQuality: true,
});

var thumbsObject = {}
axios.get('/videos/' + videoId + '/progressbar_thumbs')
    .then(function (response) {
        if (response.data && (response.data.length > 0)) {
            thumbs = response.data.map(t => {
                return {
                    src: t.thumbnail,
                    width: '150px',
                    height: '71px'
                };
            })
            thumbsObject[0] = thumbs[0]
            thumbs.forEach(function (t, index) {
                thumbsObject[parseInt(index + 1)] = t
            })
        }
    })
    .catch(function (error) {})
player.thumbnails(thumbsObject);
