require('./bootstrap');
require('./videojs');

window.Vue = require('vue').default;

Vue.config.ignoredElements = ['video-js']


Vue.component('subscribe-button',
    require('./components/subscribe-button').default
)

Vue.component('votes',
    require('./components/votes').default
)


require('./components/channel-uploads');

const app = new Vue({
    el: '#app',
});
