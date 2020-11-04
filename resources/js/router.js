
import Vue from 'vue';
import VueRouter from 'vue-router';


Vue.use(VueRouter)

import example from './components/ExampleComponent.vue';
import test from './components/test.vue';
const routes = [
    {
        path: '/example',
        component: example
    },
    {
        path: '/test',
        component: test
    }
]

export default new VueRouter({ mode: 'history', routes: routes })