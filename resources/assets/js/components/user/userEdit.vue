<template>
	<div class="jumbotron mt-5">
		<h2>Edit Profile</h2>
		<div class="row">
			<div class="col-3">
				<img class="img-fluid img-thumbnail " :src="this.image">

				<label v-if="image == originalImage" for="file-upload" class="btn btn-primary mt-2">
					<i class="fa fa-cloud-upload"></i> Insert New Image</label>
					<input id="file-upload" type="file" @change="onFileChange"/>

					<button v-if="image != originalImage" @click="removeImage" class="btn btn-warning mt-2">Remove image</button>
				</div>
				<div class="col-9">
					<div class="form-group">
						<label for="inputName">Name</label>
						<input
						type="text" class="form-control" v-model="user.name"
						name="name" id="inputName" 
						placeholder="Fullname"/>
					</div>
					<div class="form-group">
						<label for="inputEmail">Email</label>
						<input
						type="email" class="form-control" v-model="user.email"
						name="email" id="inputEmail"
						placeholder="Email address"/>
					</div>
					<div class="form-group">
						<label for="inputNickname">Nickname</label>
						<input
						type="text" class="form-control" v-model="user.nickname"
						name="nickname" id="inputNickname" 
						placeholder="Nickname"/>
					</div>
					<div class="form-group">
						<a class="btn btn-default" v-on:click.prevent="saveProfile()">Save</a>
						<a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
					</div>
				</div>
			</div>
		</div>
	</template>

	<script type="text/javascript">
	import User from '../../classes/user.js';

	export default {
		props: ['user'],
		data: function () {
			return {
				image: '',
				imageName: '',
				originalImage: ''
			}
		},
		methods: {
			saveProfile: function(){
				axios.put('api/users/'+this.user.id, {'name': this.user.name, 'email': this.user.email,'nickname': this.user.nickname}, this.$root.headers)
				.then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	if (this.user.nickname == this.$root.user.nickname) {
	                		this.$root.user.parse(this.user);
	                	}
	                });
				if (this.image != this.imageName) {
					console.log(this.user.id);
					axios.put('api/users/'+this.user.id+'/avatar', {'avatar': this.image}, this.$root.headers)
					.then(response=>{
	                	Object.assign(this.user, response.data.data);
	                	if (this.user.nickname == this.$root.user.nickname) {
	                		this.$root.user.parse(this.user);
	                	}
	                	this.$emit('user-saved', this.user);
	                });
				}


			},
			cancelEdit: function(){
				this.$emit('user-canceled');
			},
			onFileChange(e) {
				var files = e.target.files || e.dataTransfer.files;
				if (!files.length)
					return;
				this.image=files[0].name;
				this.createImage(files[0]);
			},
			createImage(file) {
				var image = new Image();
				var reader = new FileReader();
				var vm = this;

				reader.onload = (e) => {
					vm.image = e.target.result;
				};
				reader.readAsDataURL(file);
			},
			removeImage: function (e) {
				this.image = this.originalImage;
				this.imageName = '';
			},
		},
		mounted: function () {
			this.originalImage = '/api/storage/user/avatar/'+this.user.avatar;
			this.image = '/api/storage/user/avatar/'+this.user.avatar;
		},
		beforeRouteEnter (to, from, next) {
			setTimeout(function(){ 
				next();
			}, 3000);
		},
	}
	</script>

	<style scoped>	
	input[type="file"] {
		display: none;
	}
	.custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 6px 12px;
		cursor: pointer;
	}
	</style>