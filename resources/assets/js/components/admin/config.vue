<template>
	<div>
		<div class="jumbotron">
			<h1>{{ title }}</h1>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
		  <li class="nav-item">
		    <a class="nav-link active" data-toggle="tab" href="#admin-password" role="tab">Change Administrator Password</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Change Administrator Email</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" data-toggle="tab" href="#messages" role="tab">Change Platform Email</a>
		  </li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content border-1 border-top-0">
		  <div class="tab-pane active mt-2" id="admin-password" role="tabpanel">
		  	<admin-password></admin-password>
		  </div>
		  <div class="tab-pane mt-2" id="profile" role="tabpanel">
		  	<admin-email></admin-email>
		  </div>
		  <div class="tab-pane mt-2" id="messages" role="tabpanel">
		  	<platform-email></platform-email>			
		  </div>
		</div>
	</div>				
</template>

<script type="text/javascript">
	import EditPassword from './editAdminPass.vue';
	import ChangeAdminEmail from './changeAdminEmail.vue';
	import ChangePlatformEmail from './changePlatformEmail.vue';
	export default {
		data: function(){
			return { 
		        title: 'Administration Configurations'
			}
		},
	    methods: {
	        
	    },
	    components: {
	    	'admin-password': EditPassword,
	    	'admin-email': ChangeAdminEmail,
	    	'platform-email': ChangePlatformEmail
	    },
	    mounted() {
			
		}

	}
</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>