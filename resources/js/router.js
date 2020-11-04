
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter)

import tests from './components/tests.vue';
import home_page from './components/home_page.vue';
const routes = [
    {   //главная страница
        path:'/',
        component: home_page
    },
    {   //тесты
        path: '/tests',
        component: tests
    }
]

export default new VueRouter({ mode: 'history', routes: routes })