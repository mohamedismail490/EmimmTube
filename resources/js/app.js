require('./bootstrap');

window.Vue = require('vue').default;

require('./components/subscribe-button');
require('./components/channel-uploads');

const app = new Vue({
    el: '#app',
});
