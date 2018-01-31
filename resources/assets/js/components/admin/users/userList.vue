<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Nickname</th>
				<th>Points</th>
				<th>Games</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="user in users"  :key="user.id" :class="{activerow: editingUser === user} ">
				<td>{{ user.name }}</td>
				<td>{{ user.email }}</td>
				<td>{{ user.nickname }}</td>
				<td>{{ user.total_points}}</td>
				<td>{{ user.total_games_played}}</td>
				<td>
					<div class="btn-group col-12" role="group" aria-label="Basic example">
						<a class="btn btn-xs btn-primary col-4" v-on:click.prevent="editUser(user)">Edit</a>
						<a class="btn btn-xs btn-warning col-4" v-on:click.prevent="blockUser(user)" v-if="!user.blocked">Block</a>
						<a class="btn btn-xs btn-warning col-4" v-on:click.prevent="blockUser(user)" v-if="user.blocked">Unblock</a>
						<a class="btn btn-xs btn-danger col-4" v-on:click.prevent="deleteUser(user)">Delete</a>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script type="text/javascript">
// Component code (not registered)
module.exports={
	props: ['users'],
	data: function(){
		return { 
			editingUser: null
		}
	},
	methods: {
		editUser: function(user) {
			this.editingUser = user;
			this.$emit('edit-click', user);
		},		
		deleteUser: function(user) {
			this.editingUser = null;
			this.$emit('delete-click', user);
		},
		blockUser: function(user) {
			this.editingUser = user;
			this.$emit('block-click', user);
		},
		unblockUser: function(user) {
			this.editingUser = user;
			this.$emit('unblock-click', user);
		}
	},		
}
</script>

<style scoped>
tr.activerow {
	background: #123456  !important;
	color: #fff          !important;
}
.table td {
	border-top: 0px !important;
}

</style>