<template>
	<div>
		<form>
			<div class="form-group">
				<label for="Email1">Administrator's Email Address</label>
				<input type="email" class="form-control" id="Email1" aria-describedby="emailHelp" v-model="newAdminEmail" placeholder="New Administrator Email">
			</div>
			<div class="form-group">
				<label for="ConfirmPassword">Insert Password To Confirm</label>
				<input type="password" class="form-control" id="ConfirmPassword" placeholder="Password" v-model="passwordToChangeAdminEmail">
			</div>
			<button type="submit" class="btn btn-primary" @click.prevent="changeAdminEmail">Change Email</button>
		</form>
		<div class="alert alert-success mt-2" v-if="success">
			<button type="button" class="close-btn" v-on:click="success=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
	</div>
</template>

<script type="text/javascript">
	import Errors from'../../../classes/errors.js';
	export default {
		data: function(){
			return {
				newAdminEmail: '',
				passwordToChangeAdminEmail: '',
				submited: false,
				successMessage: '',
				success: false,
				errors: new Errors(),
			}
		},
		computed: {
			missingEmail: function () {
				return this.newAdminEmail.trim() === '' && this.submited;
			},
			missingPassword: function () {
				return this.passwordToChangeAdminEmail.trim() === '' && this.submited;
			},
			invalidEmail: function () {
				return !this.validateEmail(this.newAdminEmail) && this.submited;
			},
			hasErrors: function () {
				return (this.missingPassword || this.missingEmail || this.invalidEmail);
			}
		},
		methods: {
			validateEmail: function (email) {
			    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			    return re.test(email.toLowerCase());
			},
			changeAdminEmail: function () {
				this.success = false;

				this.submited = true;

				if (!this.hasErrors) {
					const data = {
						"newAdminEmail": this.newAdminEmail,
						"password": this.passwordToChangeAdminEmail
					};
					axios.put('api/user/email/update', data, this.$root.headers)
					.then((response) => {
						this.success = true;
						this.successMessage = 'Administrator Email Changed.';
						this.reset();
					})
					.catch((error) => {
						this.errors.record(error.response.data);
					});
				}
			},
			reset: function () {
				this.submited = false;
				this.newAdminEmail = '';
				this.passwordToChangeAdminEmail = '';
			}
		}
	}
</script>