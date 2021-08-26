require('./bootstrap');
require('./videojs');
require('videojs-theater-mode/dist/videojs.theaterMode');
require('videojs-theater-mode/dist/videojs.theaterMode.css');
require('@hola.org/videojs-thumbnails/videojs.thumbnails')
require('@hola.org/videojs-thumbnails/videojs.thumbnails.css')
require('videojs-contrib-quality-levels');
require('videojs-hls-quality-selector');
require('videojs-hotkeys/videojs.hotkeys');


window.Vue = require('vue').default;

Vue.config.ignoredElements = ['video-js']


Vue.component('subscribe-button',
    require('./components/subscribe-button').default
)

Vue.component('votes',
    require('./components/votes').default
)

Vue.component('comments',
    require('./components/comments').default
)

Vue.component('channel-videos',
    require('./components/channel-videos').default
)


require('./components/channel-uploads');

const app = new Vue({
    el: '#app',
});
