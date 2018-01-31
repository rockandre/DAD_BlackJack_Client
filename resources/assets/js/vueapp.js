
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

import VueRouter from 'vue-router';
import User from './classes/user.js';
import VueSocketio from 'vue-socket.io';

window.Vue = require('vue');


Vue.use(VueRouter);

Vue.use(VueSocketio, 'http://blackjack.dad:8080');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.component('navbar', require('./components/Navbar.vue'));
 const login = Vue.component('login', require('./components/Login.vue'));
 const home = Vue.component('home', require('./components/Home.vue'));
 const passwordRequest = Vue.component('password-request-component', require('./components/passwordReset/password_request.vue'));
 const passwordReset = Vue.component('password-reset-component', require('./components/passwordReset/password_reset.vue'));
 // game components
 const gamelobby = Vue.component('gamelobby', require('./components/game/gamelobby.vue'));
 const gamearea = Vue.component('gamearea', require('./components/game/game-area.vue'));

 // admin components
 const adminDashboard = Vue.component('dashboard-component', require('./components/admin/dashboard.vue'));
 const adminUsers = Vue.component('users-component', require('./components/admin/users/user.vue'));
 const adminDecks = Vue.component('deck-component', require('./components/admin/decks/deck.vue'));
 const adminDecksAdd = Vue.component('deck-add-component', require('./components/admin/decks/deckAdd.vue'));
 const adminConfig = Vue.component('admin-config', require('./components/admin/settings/config.vue'));

 const routes = [
 { path: '/', component: home },
 { path: '/login', component: login },
 { path: '/password/reset', component: passwordRequest },
 { path: '/password/reset/:token', component: passwordReset, props: true  },
 // game routes
 { path: '/gamelobby', component: gamelobby},
 { path: '/game', component: gamearea},

 //admin routes
 { path: '/admin', redirect: '/admin/dashboard'},
 { path: '/admin/dashboard', component: adminDashboard},
 { path: '/admin/users', component: adminUsers },
 { path: '/admin/config', component: adminConfig},
 { path: '/admin/decks', component: adminDecks},
 { path: '/admin/decks/add', component: adminDecksAdd}
 ];

 const router = new VueRouter({
 	routes:routes
 });

 const app = new Vue({
 	router,
 	el: '#app',
 	data: {
 		user: new User(),
 		headers: {},
 	}
 });

 
