<template>	
	<div class="padding-top">	
		<div class="card text-center border-primary mx-auto col-5">
			<div class="card-header">
				<ul class="nav nav-tabs card-header-tabs nav-justified" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#login" role="tab">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#register" role="tab">Register</a>
					</li>
				</ul>
			</div>
			<div class="card-body">
				<div class="tab-content">
					<div class="tab-pane active" id="login" role="tabpanel">
						<form>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="email">Email</label>
								<div class="col-8">
									<input class="form-control" type="text" name="email" v-model="user.email">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="password">Password</label>
								<div class="col-8">
									<input class="form-control" type="password" name="password" v-model="user.password">
								</div>
							</div>
							<a type="submit" class="btn btn-primary" @click.prevent="login">Login</a>
						</form>
					</div>
					<div class="tab-pane" id="register" role="tabpanel">
						<form>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="name">Name</label>
								<div class="col-8">
									<input class="form-control" type="text" name="name" v-model="user.name">
								</div>
								<div class="col-offset-1 col-11">
									<span v-if="errors.has('name')"
									class="text-danger">
									<strong>{{errors.get('name')}}</strong></span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="email">Email</label>
								<div class="col-8">
									<input class="form-control" type="text" name="email" v-model="user.email">
								</div>
								<div class="col-offset-1 col-11">
									<span v-if="errors.has('email')"
									class="text-danger">
									<strong>{{errors.get('email')}}</strong></span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="nickname">Nickname</label>
								<div class="col-8">
									<input class="form-control" type="text" name="nickname" v-model="user.nickname">
								</div>
								<div class="col-offset-1 col-11">
									<span v-if="errors.has('nickname')"
									class="text-danger">
									<strong>{{errors.get('nickname')}}</strong></span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="password">Password</label>
								<div class="col-8">
									<input class="form-control" type="password" name="password" v-model="user.password">
								</div>
								<div class="col-offset-1 col-11">
									<span v-if="errors.has('password')"
									class="text-danger">
									<strong>{{errors.get('password')}}</strong></span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="password_confirm">Confirm Password</label>
								<div class="col-8">
									<input class="form-control" type="password" name="password_confirm" v-model="user.password_confirmation">
								</div>
							</div>
							<a type="submit" class="btn btn-primary" @click.prevent="register">Create Account</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import User from '../classes/user.js';
import Errors from '../classes/errors.js';
export default {
	data: function(){
		return { 
			user: new User(),
			errors: new Errors(),
		}
	},
	methods: {
		login: function() {
			let authToken = undefined;
			axios.post("api/login", {
				"email": this.user.email,
				"password": this.user.password
			}).then(response => {
				this.user = new User();
				this.$root.user.access_token = response.data.access_token;
				this.$root.user.refresh_token = response.data.refresh_token;
				this.authToken = response.data.access_token;
				this.$root.headers = {headers: {
					"Accept": "application/json",
					"Authorization": ("Bearer "+ this.$root.user.access_token),
				}};
				axios.get("api/user", this.$root.headers
				).then(response => {
					if(response.data.activated == 0) {
						axios.post("api/logout", null, {
							headers: {
								"Accept": "application/json",
								"Authorization": ("Bearer "+ this.$root.user.access_token),
							}
						})
						.then(response => {
							alert("Activate your account first! Check your email!");
							this.$root.user = new User();
							this.$root.headers = {};
							window.localStorage.removeItem('authToken');
						});
					} else {
						this.$root.user.parse(response.data);
						window.localStorage.setItem('authToken', this.authToken);
					}
					
				});
				this.$router.push("/");
			});
		},
		register: function() {
			this.errors = new Errors();
			axios.post("api/register", this.user)
			.then(response => {
				this.user = new User();
			})
			.catch(errors => {
				this.errors.record(errors.response.data);
				this.user = new User();
			});
		}
	},
	mounted() {

	}
}
</script>
<style>
.col-5 {
	padding: 0;
}

.padding-top {
	padding-top: 55px;
}

</style>