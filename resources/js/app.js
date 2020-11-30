
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import axios from 'axios'
import VueAxios from 'vue-axios'
import store from './store';

import Error from './components/Misc/Error.vue';
Vue.component('Error', Error);
import Loader from './components/Misc/Loader.vue';
Vue.component('Loader', Loader);

import LastFive from './components/Profile/BasicStats/LastFive.vue';
Vue.component('LastFive', LastFive);
import HoursAndMinutes from './components/Profile/BasicStats/HoursAndMinutes.vue';
Vue.component('HoursAndMinutes', HoursAndMinutes);
import LongestAndShortest from './components/Profile/BasicStats/LongestAndShortest.vue';
Vue.component('LongestAndShortest', LongestAndShortest);
import FavoriteGenres from './components/Profile/BasicStats/FavoriteGenres.vue';
Vue.component('FavoriteGenres', FavoriteGenres);

Vue.use(VueAxios, axios);

import router from './router';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    store,
    el: '#app',
    router
});