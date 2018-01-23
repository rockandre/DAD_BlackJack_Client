
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 import VueRouter from 'vue-router';
 import User from './classes/user.js';

 window.Vue = require('vue');


 Vue.use(VueRouter);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.component('navbar', require('./components/Navbar.vue'));
 const login = Vue.component('login', require('./components/Login.vue'));
 const home = Vue.component('home', require('./components/Home.vue'));


 const routes = [
 { path: '/', component: home },
 { path: '/login', component: login },
 ];

 const router = new VueRouter({
 	routes:routes
 });

 const app = new Vue({
 	router,
 	el: '#app',
 	data: {
 		user: new User(),
 	}
 });
