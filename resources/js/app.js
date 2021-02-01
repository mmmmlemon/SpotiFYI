
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
import Info from './components/Misc/Info.vue';
Vue.component('Info', Info);
import BackgroundImage from './components/Misc/BackgroundImage.vue';
Vue.component('BackgroundImage', BackgroundImage);
import BackgroundImageFront from './components/Misc/BackgroundImageFront.vue';
Vue.component('BackgroundImageFront', BackgroundImageFront);

import LastFive from './components/Profile/BasicStats/LastFive.vue';
Vue.component('LastFive', LastFive);
import HoursAndMinutes from './components/Profile/BasicStats/HoursAndMinutes.vue';
Vue.component('HoursAndMinutes', HoursAndMinutes);
import LongestAndShortest from './components/Profile/BasicStats/LongestAndShortest.vue';
Vue.component('LongestAndShortest', LongestAndShortest);
import FavoriteGenres from './components/Profile/BasicStats/FavoriteGenres.vue';
Vue.component('FavoriteGenres', FavoriteGenres);
import ArtistsCount from './components/Profile/BasicStats/ArtistsCount.vue';
Vue.component('ArtistsCount', ArtistsCount);
import YearsAndDecades from './components/Profile/BasicStats/YearsAndDecades.vue';
Vue.component('YearsAndDecades', YearsAndDecades);

import Top10Items from './components/Profile/Top10/Top10Items.vue';
Vue.component('Top10Items', Top10Items);

import AchievementItem from './components/Profile/Achievements/AchievementItem.vue';
Vue.component('AchievementItem', AchievementItem);

import List from './components/Profile/Latest/List.vue';
Vue.component('List', List);

import ListItem from './components/Profile/Latest/ListItem.vue';
Vue.component('ListItem', ListItem);

import Cookies from './components/Misc/Cookies.vue';
Vue.component('Cookies', Cookies);

//графики
import BarChart from './components/Charts/BarChart.vue';
Vue.component('BarChart', BarChart);


Vue.use(VueAxios, axios);

import router from './router';

//перед перезагрузкой страницы, или перед выходом с сайта
//отправляем api-запрос на удаление папки с файлами пользователя
window.addEventListener("beforeunload", function(evt) {
    axios.get('/api/clean_user_data').then(response => {
        console.log("%cTemporary user data has been removed.", 'font-weight: bold;')
    });
});

const app = new Vue({
    store,
    el: '#app',
    router
});