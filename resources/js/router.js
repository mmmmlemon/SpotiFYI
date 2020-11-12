
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter)

import tests from './components/tests.vue';
import HomePage from './components/HomePage.vue';
import Welcome from './components/HomePage/Welcome.vue';
import About from './components/HomePage/About.vue';

const routes = [
    {   //главная страница
        path:'/',
        component: HomePage,
        children: [
            {
                path:'/',
                component: Welcome
            },
            {
                path:'about',
                component: About
            },
            {
                path:'tests',
                component: tests
            }

        ]
    },
    {   //тесты
        path: '/tests',
        component: tests
    }
]

export default new VueRouter({ mode: 'history', routes: routes })