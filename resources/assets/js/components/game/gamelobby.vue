<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Creator</th>
				<th># of Players</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="game in games"  :key="game.gameID">
				<td>{{ game.gameID }}</td>
				<td>{{ game.playerList[0].name }}</td>
				<td>{{ game.playerList.length }}</td>
				<td>
					<a class="btn btn-xs btn-primary" v-if="!alreadyIn(game)" v-on:click.prevent="join(game)">Join</a>
					<a class="btn btn-xs btn-primary" v-else v-on:click.prevent="resume(game)">Resume</a>
				</td>
			</tr>
		</tbody>
	</table>
</template>
<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		props: ['games'],
		methods: {
			join(game) {
				this.$emit('join-click', game);
			},	
			resume(game) {
				this.$emit('resume-click', game);
			},
			alreadyIn(game) {
				game.playerList.forEach(player => {
					console.log(player.name+" ---- "+this.$root.user.nickname);
					if(player.name == this.$root.user.nickname) {
						return true;
					}
				});
				return false;
			},
		},
	}
</script>

<style scoped>

</style>