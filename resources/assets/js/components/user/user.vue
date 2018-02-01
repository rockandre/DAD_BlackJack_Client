<template>
	<div>
		<user-info></user-info>
	</div>
</template>

<script type="text/javascript">
import UserInfo from './userInfo.vue';

export default {
	beforeRouteEnter (to, from, next) {
		setTimeout(function(){ 
			next();
		}, 3000);
	},
	components: {
		'user-info': UserInfo,

	}
}
</script>