<template>
	<div>
		<h4>Platform Email</h4>
		<form>
			<div class="form-group">
				<label for="platEmailDriver">Driver</label>
				<input type="text" class="form-control" id="platEmailDriver" placeholder="Insert Driver Here" v-model="setting.driver">
			</div>
			<div class="form-group">
				<label for="platEmailHost">Host</label>
				<input type="text" class="form-control" id="platEmailHost" placeholder="Insert Host Here" v-model="setting.host">
			</div>
			<div class="form-group">
				<label for="platEmailPort">Port</label>
				<input type="number" class="form-control" id="platEmailPort" placeholder="Insert Port Here" v-model="setting.port">
			</div>
			<div class="form-group">
				<label for="platEmail">Email</label>
				<input type="email" class="form-control" id="platEmail" placeholder="Insert Email Here" v-model="setting.email">
			</div>
			<div class="form-group">
				<label for="platEmailPassword">Password</label>
				<input type="password" class="form-control" id="platEmailPassword" placeholder="Insert Password Here" v-model="setting.password">
			</div>
			<div class="form-group">
				<label for="platEmailEncryption">Encryption</label>
				<input type="text" class="form-control" id="platEmailEncryption" placeholder="Insert Encryption Here" v-model="setting.encryption">
			</div>
			<div class="form-group">
				<label for="passwordToSave1">Insert Password To Confirm</label>
				<input type="password" class="form-control" id="passwordToSave1" placeholder="Insert Administrator Password Here" v-model="passwordToChangePlatformEmail">
			</div>
			<button type="submit" class="btn btn-primary" @click.prevent="changePlatformEmail">Change Email</button>
		</form>
		<div class="alert alert-success mt-2" v-if="success">
			<button type="button" class="close-btn" v-on:click="success=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
	</div>
</template>

<script type="text/javascript">
	import Errors from'../../classes/errors.js';
	export default {
		data: function(){
			return {
				setting: {
					driver: '',
					host: '',
					port: '',
					email: '',
					password: '',
					encryption: ''
				},
				passwordToChangePlatformEmail: '',
				submited: false,
				success: false,
				successMessage: '',
				errors: new Errors(),
			}
		},
		computed: {
			missingDriver: function () {
				return this.setting.driver.trim() === '' && this.submited;
			},
			missingHost: function () {
				return this.setting.host.trim() === '' && this.submited;
			},
			missingPort: function () {
				return this.setting.port.trim() === '' && this.submited;
			},
			missingEmail: function () {
				return this.setting.email.trim() === '' && this.submited;
			},
			missingPassword: function () {
				return this.setting.password.trim() === '' && this.submited;
			},
			missingencryption: function () {
				return this.setting.encryption.trim() === '' && this.submited;
			},
			missingAdminPassword: function () {
				return this.passwordToChangePlatformEmail.trim() === '' && this.submited;
			},
			invalidEmail: function () {
				return !this.validateEmail(this.setting.email.trim()) && this.submited;
			},
			invalidPort: function () {
				return !this.missingPort && !this.validatePortNumber(this.setting.port) && this.submited;
			},
			hasErrors: function () {
				return (this.missingDriver || this.missingHost || this.missingPort || this.missingEmail || this.missingPassword || this.missingencryption || this.missingAdminPassword || this.invalidEmail);
			}
		},
		methods: {
			validateEmail: function (email) {
			    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			    return re.test(email.toLowerCase());
			},
			validatePortNumber: function (number) {
                var re = /^[1-9][0-9]*$/;
                return re.test(number);
            },
			changePlatformEmail: function () {
				this.success = false;

				this.submited = true;

				if (!this.hasErrors) {
					this.success = false;
					this.encryption = true;

					const data = {
                        driver:  this.setting.driver,
                        host:  this.setting.host,
                        port:  this.setting.port,
                        email:  this.setting.email,
                        password: this.setting.password,
                        encryption: this.setting.encryption
                    };
                    axios.post('/api/settings/update', data, this.$root.headers)
                        .then((response) => {
                            this.success = true;
                            this.successMessage = 'Platform Email Changed.';
                            this.encryption = false;
                            this.reset();
                        })
                        .catch((error) => {
                           this.errors.record(error.response.data);
                        });

				}
			},
			reset: function () {
				this.setting.driver = '';
				this.setting.host = '';
				this.setting.port = '';
				this.setting.email = '';
				this.setting.password = '';
				this.setting.encryption = '';
				this.passwordToChangePlatformEmail = '';
			}
		}
	}
</script>