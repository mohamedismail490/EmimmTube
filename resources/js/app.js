require('./bootstrap');

window.Vue = require('vue').default;

Vue.config.ignoredElements = ['video-js']
require('./components/subscribe-button');
require('./components/channel-uploads');

const app = new Vue({
    el: '#app',
});
