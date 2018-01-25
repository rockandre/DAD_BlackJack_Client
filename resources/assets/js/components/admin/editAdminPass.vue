<template>
	<div>
	<form> 
		<div class="form-group">
			<label for="exampleInputPassword1">Current Password</label>
			<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Current Password" v-model="currentPassword">
			<div class="col-offset-1 col-11">
				<span v-if="errors.has('currentPassword')"
				class="text-danger">
				<strong>{{errors.get('currentPassword')}}</strong></span>
			</div>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword2">New Password</label>
			<input type="password" class="form-control" id="exampleInputPassword2" placeholder="New Password" v-model="newPassword">
			<div class="col-offset-1 col-11">
				<span v-if="errors.has('newPassword')"
				class="text-danger">
				<strong>{{errors.get('newPassword')}}</strong></span>
			</div>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword3">Confirm New Password</label>
			<input type="password" class="form-control" id="exampleInputPassword3" placeholder="Confirm New Password" v-model="confirmNewPassword">
		</div>
		<button type="submit" class="btn btn-primary" @click.prevent="changePassword">Change Password</button>
	</form>
</div>
</template>

<script type="text/javascript">
import Errors from'../../classes/errors.js';
export default {
	data: function(){
		return {
			currentPassword: '',
			newPassword: '',
			confirmNewPassword: '',
			submited: false,
			errorMessage: '',
			success: false,
			errors: new Errors(),
		}
	},
	computed: {
		missingCurrentPassword: function () {
			return this.currentPassword.trim() === '' && this.submited;
		},
		missingNewPassword: function () {
			return this.newPassword.trim() === '' && this.submited;
		},
		missingConfirmNewPassword: function () {
			return this.confirmNewPassword.trim() === '' && this.submited;
		},
		invalidNewPassword: function () {
			return !this.missingNewPassword && !this.validatePassword(this.newPassword.trim()) && this.submited;
		},
		invalidConfirmNewPassword: function () {
			return !this.missingConfirmNewPassword && !this.validatePassword(this.confirmNewPassword.trim()) && this.submited;
		},
		newPasswordsDontMatch: function () {
			return !this.missingNewPassword && !this.invalidNewPassword && !this.missingConfirmNewPassword && !this.invalidConfirmNewPassword && (this.newPassword.trim() != this.confirmNewPassword.trim()) && this.submited;
		},
		hasErrors: function () {
			return (this.missingCurrentPassword || this.missingNewPassword || this.missingConfirmNewPassword || this.newPasswordsDontMatch);
		},
	},
	methods: {
		validatePassword: function (password) {
			var re = /^[a-zA-Z0-9]{6,}$/;
			return re.test(password);
		},
		changePassword: function () {
			this.success = false;

			this.submited = true;

			if (!this.hasErrors) {

				const data = {
					"currentPassword": this.currentPassword,
					"newPassword": this.newPassword,
					"newPassword_confirmation": this.confirmNewPassword,
				};
				axios.put('api/user/password/update', data, this.$root.headers)
				.then((response) => {
					this.success = true;
					this.cancel();
				})
				.catch((error) => {
					this.errors.record(error.response.data);
				});
			}
		},
		cancel: function () {
			this.submited = false;
			this.currentPassword = '';
			this.newPassword = '';
			this.confirmNewPassword = '';
		},
	}
}
</script>