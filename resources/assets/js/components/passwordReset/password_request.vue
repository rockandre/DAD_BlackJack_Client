<template>
    <div>
        <form method="post" v-on:submit="sendForm">
            <div class="form-group">
                <h3 class="mt-3">Recovery your Password</h3>
                <input type="text" name="email" v-model="email" class="form-control" placeholder="Email"  />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Send Email</button>
                <router-link to="/login" class="btn btn-danger ml-2">Back to login</router-link>
            </div>
            <div class="alert alert-danger" role="alert" v-cloak v-show="hasErrorSubmitted">
                <button type="button" class="close-btn" v-on:click="errors=false">&times;</button>
                <strong class="ml-2" v-if="isEmailEmpty && this.submitted">Insert a email.</strong>
                <strong class="ml-2" v-if="isInvalidEmail && this.submitted">Insert a valid email.</strong>
            </div>

            <div class="alert alert-success" role="alert" v-cloak v-show="success">
                <button type="button" class="close-btn" v-on:click="errors=false">&times;</button>
                <strong class="ml-2">Email sended.</strong>
            </div>
        </form>
    </div>
</template>

<script type="text/javascript">
    export default {
        data: function(){
            return {
                email: '',
                submitted: false,
                success: false,
                errors: false
            }
        },
        computed: {
            isEmailEmpty: function () {
                return this.email.trim() === '' && this.submitted;
            },
            isInvalidEmail: function () {
                return !this.isEmailEmpty && !this.validateEmail(this.email.trim()) && this.submitted;
            },
            hasError: function () {
                return (this.isEmailEmpty || this.isInvalidEmail);
            },
            hasErrorSubmitted: function () {
                return this.hasError && this.submitted;
            },
        },
        methods: {
            validateEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            sendForm: function () {
                this.success = false;
                event.preventDefault();
                this.submitted = true;
                this.errors = this.hasError;
                if (!this.hasError) {                    
                    axios.post('/api/password/email', {'email': this.email})
                        .then((response) => {
                                this.success = true;
                                this.submitted = false;
                        });
                    this.success = true;
                }
            }
        }
    }
</script>