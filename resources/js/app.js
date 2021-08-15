require('./bootstrap');

window.Vue = require('vue').default;

require('./components/subscribe-button');

const app = new Vue({
    el: '#app',
});
