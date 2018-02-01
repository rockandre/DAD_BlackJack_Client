<template>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
		<div class="container">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<router-link to="/" class="navbar-brand">BlackJack</router-link>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav mr-auto" v-if="this.$root.user.logged()">
					<li class="nav-item">
						<router-link to="/game" class="nav-link">Join Game</router-link>
					</li>
				</ul>
				<ul class="navbar-nav justify-content-end">
					<li class="nav-item dropdown" v-if="this.$root.user.logged() && this.$root.user.admin">
						<a class="nav-link nav-item dropdown-toggle" href="#" id="navbarDropdownAdminMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Administration Panel
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownAdminMenuLink">
							<router-link to="/admin" class="dropdown-item">Dashboard</router-link>
							<router-link to="/admin/users" class="dropdown-item">Users</router-link>
							<router-link to="/admin/decks" class="dropdown-item">Deck</router-link>
							<router-link to="/admin" class="dropdown-item">Statistics</router-link>
							<div class="dropdown-divider"></div>
							<router-link to="/admin/config" class="dropdown-item">Administration Settings</router-link>
						</div>
					</li>
					<li class="nav-item" v-if="!this.$root.user.logged()">
						<router-link to="/login" class="nav-link">Login / Register</router-link>
					</li>
					<li class="nav-item dropdown" v-else>
						<a class="nav-link nav-item dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Welcome, <b>{{this.$root.user.name}}</b>
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<router-link to="/user" class="dropdown-item">Profile</router-link>
							<a class="dropdown-item" href="#" @click.prevent="logout">Logout</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</template>
<script type="text/javascript">
import User from '../classes/user.js';
export default {
	props: ['activated'],
	data: function(){
		return {
		}
	},
	methods: {
		isAdmin: function() {
			if (this.$root.user.logged())
				if(this.$root.user == 1) 
					return true;
				return false
			},
			logout: function() {
				axios.post("api/logout", null, this.$root.headers)
				.then(response => {
					this.$root.user = new User();
					this.$root.headers = {};
					window.localStorage.removeItem('authToken');
					this.$router.push('/');
				});
			},
		},
		created() {
			this.$root.user.access_token = (window.localStorage.getItem('authToken') === null ? '' : window.localStorage.getItem('authToken'));
			if (this.$root.user.access_token != '') {
				this.$root.headers = {headers: {
					"Accept": "application/json",
					"Authorization": ("Bearer "+ this.$root.user.access_token),
				}};
				axios.get("api/user", this.$root.headers).then(response => {
					this.$root.user.parse(response.data);
				}).catch(response => {
					this.$root.user = new User();
					window.localStorage.removeItem('authToken');
				});
			}
		},
		mounted() {
			if(this.activated == 1) {
				alert('Account activated with success! You can now login!');
				this.$router.push('/login');
			}
		}
	}
	</script>
	<style type="text/css">

	</style>