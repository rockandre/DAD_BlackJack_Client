<template>
	<div>
		<div class="jumbotron">
			<h1>{{ title }}</h1>
		</div>

		<user-list :users="users" @edit-click="editUser" @delete-click="deleteUser" @block-click="blockUser" @message="childMessage" ref="usersListRef"></user-list>

		<div class="alert alert-success" v-if="showSuccess">
			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
		<user-edit :user="currentUser" @user-saved="savedUser" @user-canceled="cancelEdit" v-if="currentUser && action == 1"></user-edit>			
		<user-block :user="currentUser" @user-saved="savedUser" @user-canceled="cancelEdit" v-if="currentUser && action == 2"></user-block>
	</div>				
</template>

<script type="text/javascript">
	import UserList from './userList.vue';
	import UserEdit from './userEdit.vue';
	import UserReason from './userReason.vue';
	
	export default {
		data: function(){
			return { 
		        title: 'List Users',
		        showSuccess: false,
		        successMessage: '',
		        currentUser: null,
		        action: 0,
		        users: []
			}
		},
	    methods: {
	        editUser: function(user){
	            this.currentUser = user;
	            this.action = 1;
	            this.showSuccess = false;
	        },
	        blockUser: function(user) {
	        	this.currentUser = user;
	        	this.action = 2;
	            this.showSuccess = false;
	        },
	        deleteUser: function(user){
	            axios.delete('api/users/'+user.id, this.$root.headers)
	                .then(response => {
	                    this.showSuccess = true;
	                    this.successMessage = 'User Deleted';
	                    this.getUsers();
	                });
	        },
	        savedUser: function(){
	            this.currentUser = null;
	            this.action = 0;
	            this.$refs.usersListRef.editingUser = null;
	            this.showSuccess = true;
	            this.successMessage = 'User Saved';
	        },
	        cancelEdit: function(){
	            this.currentUser = null;
	            this.action = 0;
	            this.$refs.usersListRef.editingUser = null;
	            this.showSuccess = false;
	        },
	        getUsers: function(){
	            axios.get('api/users', this.$root.headers)
	                .then(response=>{this.users = response.data.data; });
			},
			childMessage: function(message){
				this.showSuccess = true;
	            this.successMessage = message;
			}
	    },
	    components: {
	    	'user-list': UserList,
	    	'user-edit': UserEdit,
	    	'user-block': UserReason
	    },
	    mounted() {
			this.getUsers();
		}

	}
</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>