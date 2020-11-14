
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter)

import tests from './components/tests.vue';
import HomePage from './components/HomePage.vue';
import Welcome from './components/HomePage/Welcome.vue';
import About from './components/HomePage/About.vue';
import Profile from './components/Profile.vue';
import BasicStats from './components/Profile/BasicStats.vue';
import Top10 from './components/Profile/Top10.vue';

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
    {
        path:'/profile',
        component: Profile,
        children: [
            {
                path:'/profile',
                component: BasicStats
            },
            {
                path:'/profile/top10tracks',
                component: Top10
            }

        ]
    }

]

export default new VueRouter({ mode: 'history', routes: routes })