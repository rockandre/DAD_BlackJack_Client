<template>
	<div class="jumbotron">
		<!-- if not blocked -->
		<div v-if="!user.blocked">
			<h2>Block User</h2>
			<div class="form-group">
				<label for="inputName">Reason to block</label>
				<input
				type="text" class="form-control" v-model="user.reason_blocked"
				name="reasonBlocked" id="inputReasonBlocked" 
				placeholder="Reason to Block"/>
			</div>

			<div class="form-group">
				<a class="btn btn-default" v-on:click.prevent="blockUser()">Submit</a>
			</div>
		</div>


		<!-- if blocked -->
		<div v-if="user.blocked">
			<h2>Unblock User</h2>
			<div class="form-group">
				<label for="inputName">Reason to unblock</label>
				<input
				type="text" class="form-control" v-model="user.reason_reactivated"
				name="reasonReactivated" id="inputReasonReactivated" 
				placeholder="Reason to Unlock"/>
			</div>

			<div class="form-group">
				<a class="btn btn-default" v-on:click.prevent="unblockUser()">Submit</a>
			</div>
		</div>

	</div>
</template>

<script type="text/javascript">
module.exports={
	props: ['user'],
	data: function(){
		return { 
		}
	},
	methods: {
		blockUser: function() {
			this.user.blocked = 1;
			this.saveUser();
		},
		unblockUser: function() {
			this.user.blocked = 0;
			this.saveUser();
		},
		saveUser: function(){
			axios.put('api/users/'+this.user.id, this.user, this.$root.headers)
			.then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-saved', this.user);
	                });
		},
		cancelEdit: function(){
			axios.get('api/users/'+this.user.id, this.$root.headers)
			.then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-canceled', this.user);
	                });
		}
	}
}
</script>

<style scoped>	

</style>