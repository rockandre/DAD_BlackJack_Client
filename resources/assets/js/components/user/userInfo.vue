<template>
	<div>
		<div class="card mt-5" v-if="this.action == 0">
			<h3 class="card-header">User Profile</h3>
			<div class="card-block">
				<div class="row mt-2">
					<div class="col-3">
						<img :src="'/api/storage/user/avatar/'+this.$root.user.avatar" id="imageid" class="img-fluid img-thumbnail">
					</div>
					<div class="col-9">
						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								<p class="col-3 font-weight-bold d-inline">Name</p>
								<p class="col-9 d-inline">{{user.name}}</p>
							</li>
							<li class="list-group-item">
								<p class="col-3 font-weight-bold d-inline">Email</p>
								<p class="col-9 d-inline">{{user.email}}</p>
							</li>
							<li class="list-group-item">
								<p class="col-3 font-weight-bold d-inline">Nickname</p>
								<p class="col-9 d-inline">{{user.nickname}}</p>
							</li>
							<li class="list-group-item">
								<a class="btn btn-primary d-inline" v-on:click.prevent="editing()">Edit Profile</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<user-edit :user="user" @user-saved="userSaved" @user-canceled="cancelEdit" v-if="action == 1"></user-edit>
	</div>
</template>

<script>
	import User from '../../classes/user.js';
	import UserEdit from './userEdit.vue';
	export default {
		data: function() {
            return {
            	user: new User(),
                path: '',
                action: 0
            }
        },
        methods: {
            userSaved: function(user){
	           	window.location.reload(true);
	            this.$root.user = user;
	            this.action=0;
	        },
	        cancelEdit: function() {
				this.action=0;
	        },
	        editing: function() {
	        	this.action=1;
	        }
        },
        mounted() {
        	this.user = this.$root.user;
        },
        components: {
	    	'user-edit': UserEdit,
	    },
    }

</script>
