require('./bootstrap');

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from '@/views/App'
import CsvInput from '@/views/components/CsvInput'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'csvInput',
            component: CsvInput
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
