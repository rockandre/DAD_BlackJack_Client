<template>
    <form method="post" v-on:submit="validateForm">
        <h1 class="mt-3">Reset Password</h1>
        <div class="form-group">
            <input type="text" name="email" v-model="email" v-bind:class="{ 'is-invalid': isEmailEmpty || isEmailInvalid  }" class="form-control" placeholder="Email"  />
        </div>
        <div class="form-group">
            <input type="password" name="password" v-model="password" v-bind:class="{ 'is-invalid': isPasswordEmpty || isPasswordInvalid }" class="form-control" placeholder="New Password"  />
        </div>
        <div class="form-group">
            <input type="password" name="passwordConfirmation" v-model="passwordConfirmation" v-bind:class="{ 'is-invalid': isPasswordEmptyConfirmation || wrongPasswordConfirmation }" class="form-control" placeholder="Confirme New Password"  />
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
            <router-link to="/login" class="btn btn-danger ml-2">Back to login</router-link>
        </div>

        <div class="alert alert-danger" role="alert" v-cloak v-show="isInvalid">
            <p v-if="isEmailEmpty">Introduza o email</p>
            <p v-if="isEmailInvalid">Email inválido</p>
            <p v-if="isPasswordEmpty">Introduza a password</p>
            <p v-if="isPasswordInvalid">Password têm que ter pelo menos 6 caracteres.</p>
            <p v-if="isPasswordEmptyConfirmation">Confirme a password</p>
            <p v-if="wrongPasswordConfirmation">Confirmação da password não corresponde a password</p>
        </div>

        <div class="alert alert-success" role="alert" v-cloak v-show="success">
            <p>Password saved.</p>
        </div>
    </form>
</template>

<script type="text/javascript">
    export default {
        props: ['token'],
        data: function(){
            return {
                email: '',
                password: '',
                passwordConfirmation: '',
                submitted: false,
                success: false
            }
        },
        computed: {
            isEmailEmpty: function () {
                return this.email.trim() === '' && !this.hasServerError && this.submitted;
            },
            isEmailInvalid: function () {
                return !this.isEmailEmpty && !this.validateEmail(this.email.trim()) && !this.hasServerError && this.submitted;
            },
            isPasswordEmpty: function () {
                return this.password.trim() === '' && !this.hasServerError && this.submitted;
            },
            isPasswordInvalid: function () {
                return !this.isPasswordEmpty && !this.validatePasswordStructure(this.password.trim()) && !this.hasServerError && this.submitted;
            },
            isPasswordEmptyConfirmation: function () {
                return !this.isPasswordEmpty && !this.isPasswordInvalid && this.passwordConfirmation.trim() === '' && !this.hasServerError && this.submitted;
            },
            wrongPasswordConfirmation: function () {
                return !this.isPasswordEmpty && !this.isPasswordInvalid && !this.isPasswordEmptyConfirmation && (this.passwordConfirmation.trim() != this.password.trim()) && !this.hasServerError && this.submitted;
            },
            hasErrors: function () {
                return (this.isEmailEmpty || this.isEmailInvalid || this.isPasswordEmpty || this.isPasswordInvalid || this.wrongPasswordConfirmation || this.isPasswordEmptyConfirmation);
            },
            isInvalid: function () {
                return (this.hasErrors || this.hasServerError) && this.submitted;
            },
        },
        methods: {
            validateEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            validatePasswordStructure: function (password) {
                var re = /^[a-zA-Z0-9]{6,}$/;
                return re.test(password);
            },
            validateForm: function () {
                this.serverError = false;
                this.success = false;

                event.preventDefault();

                this.submitted = true;

                if (!this.isInvalid) {
                    const data = {
                        email: this.email,
                        token: this.token,
                        password: this.password
                    };
                    axios.post('/api/password/reset', data)
                        .then((response) => {

                            this.success = true;
                            this.submitted = false;
                            this.resetFields();

                        });
                }
            },
            resetFields: function() {
                this.email = '';
                this.password = '';
                this.passwordConfirmation = '';
            }
        }
    }
</script>