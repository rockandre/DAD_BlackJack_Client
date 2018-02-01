<template>
	<div class="container padding-top ">
		<div class="card">
			<div class="card-header">Global statistics</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
						<th>Registred Users</th>
						<th>Active Games</th>
						<th>Total Games</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						<td>{{stats.registedUsers}}</td>
						<td>{{stats.activeGames}}</td>
						<td>{{stats.totalGames}}</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
						<th>Most Games Played</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="user in stats.top5MostGames">
						<td>{{user.name}} - {{user.total_games_played}}</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
						<th>Most Points</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="user in stats.top5MostPoints">
						<td>{{user.name}} - {{user.total_points}}</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
						<th>Best Average</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="user in stats.top5BestAvg">
						<td>{{user.name}} - {{(user.total_points/(user.total_games_played)==0 ? 1 : user.total_games_played)}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div>
</template>
<script type="text/javascript">
export default {
	data: function(){
		return {
			stats: []
		}
	},
	methods: {
		getStats(){
			axios.get("api/statistics", this.$root.headers).then(response => {
						this.stats = response.data.statistics;
						console.log(response.data);
					}).catch(response => {
						console.log("Error on getting stats");
					});
		}
	},
	mounted(){
		this.getStats();
	}
}
</script>
<style>
	.padding-top {
	padding-top: 55px;
}
</style>