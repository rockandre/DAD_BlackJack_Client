window.Vue = require('vue');

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

// users
const users = Vue.component('users-component', require('./components/admin/user.vue'));
const dashboard = Vue.component('dashboard-component', require('./components/admin/dashboard.vue'));

// routes
const routes = [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', component: dashboard},
    { path: '/users', component: users },
];

const router = new VueRouter({
    routes:routes
});

const app = new Vue({
	router,
    el: '#adim-app'
});
