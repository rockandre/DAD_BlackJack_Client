<template>
	<div class="padding-top">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Game ID</th>
					<th>Game Creator</th>
					<th>Created Date</th>
					<th># of Players</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="game in games">
					<td>{{ game.id }}</td>
					<td>{{ game.created_by }}</td>
					<td>{{ game.nickname }}</td>
					<td>
						<a class="btn btn-xs btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
						<a class="btn btn-xs btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
export default {
	data: function(){
		return {
			games: {},
		}
	},
	methods: {
		getPendentGames: function() {
			axios.get('api/pendentgames', {
					headers: {
					"Accept": "application/json",
					"Authorization": ("Bearer "+ this.$root.user.access_token),
				}
			})
		}
	},
	created() {
		
	},
	mounted() {
		
	}
}
</script>
<style>
.padding-top {
	padding-top: 45px;
}
</style>