<template>
	<div class="container padding-top ">
		<div class="card" v-if="this.$root.user.logged()">
			<div class="card-header">My statistics</div>
			<div class="card-body">
				
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
import User from '../classes/user.js';
export default {
	data: function(){
		return {
		}
	},
	methods: {
		axios.get("api/user", this.$root.headers).then(response => {
						this.$root.user.parse(response.data);
					}).catch(response => {
						this.$root.user = new User();
						window.localStorage.removeItem('authToken');
					});
	}
}
</script>
<style>
	.padding-top {
	padding-top: 55px;
}
</style>