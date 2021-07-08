
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter)

import tests from './components/tests.vue';
import App from './components/App.vue';
import HomePage from './components/HomePage.vue';
import Welcome from './components/HomePage/Welcome.vue';
import About from './components/HomePage/About.vue';
import Profile from './components/Profile.vue';
import BasicStats from './components/Profile/BasicStats.vue';
import Top10 from './components/Profile/Top10.vue';
import Achievements from './components/Profile/Achievements.vue';
import RecentTracks from './components/RecentTracks.vue';
import Faq from './components/HomePage/About/FAQ.vue';
import Contacts from './components/HomePage/About/Contacts.vue';
import SiteInfo from './components/HomePage/About/SiteInfo.vue';
import NotFound from './components/Misc/NotFound.vue';

const routes = [
    {   //главная страница
        path:'/',
        component: App,
        children: [
            {
                path:'/',
                component: HomePage,
                children: [
                    {
                        path: '/',
                        component: Welcome,    
                    },
                    {
                        path:'about',
                        component: About,
                        children: [
                            {
                                path: '/about',
                                component: SiteInfo
                            },
                            {
                                path:'/about/faq',
                                component: Faq
                            },
                            {
                                path:'/about/contacts',
                                component: Contacts
                            },
                        ]
                    },
                    {
                        path:'tests',
                        component: tests
                    }
                ]
            },
            {
                // профиль
                path:'/profile',
                component: Profile,
                children: [
                    {
                        path:'/profile',
                        component: BasicStats
                    },
                    {
                        path:'/profile/top10',
                        component: Top10
                    },
                    {
                        path:'/profile/achievements',
                        component: Achievements
                    },
                ]
            },
            //последние треки
            {
                path:'/recentTracks',
                component: RecentTracks
            },
            { 
                path: '/404', 
                name: '404', 
                component: NotFound, 
            }, 
            { 
            path: '/', 
            redirect: window.location.href, 
            }
        ]
    },
   

]

export default new VueRouter({ mode: 'history', routes: routes })