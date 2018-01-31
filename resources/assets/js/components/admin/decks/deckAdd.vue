<template>
	<div>
		<div class="jumbotron">
			<h1>{{ title }}</h1>
		</div>
		<div class="form-group">
			<label for="inputName">Name</label>
			<input
			type="text" class="form-control"
			name="name" id="name" v-model="deckName"
			placeholder="Deck Name"/>
		</div>

		<div class="form-group">
			<label for="image">Hidden Face Image</label>
			<div v-if="!image">
				<input type="file" class="form-control-file" @change="onFileChange" aria-describedby="fileHelp">
			</div>
			<div v-else>
				<img :src="image" />
				<button @click="removeImage" class="btn btn-warning">Remove image</button>
			</div>
		</div>

		<div class="form-group">
			<a class="btn btn-primary" v-on:click.prevent="addDeck()">Add</a>
			<router-link to="/admin/decks" class="btn btn-danger">Cancel</router-link>
		</div>

	</div>				
</template>

<script type="text/javascript">
export default {
	data: function(){
		return { 
			title: 'Add Deck',
			deckName: '',
			image: ''
		}
	},
	methods: {
		onFileChange(e) {
			var files = e.target.files || e.dataTransfer.files;
			if (!files.length)
				return;
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
			this.image = '';
		},
		addDeck: function(){
			axios.post('api/deck', {'name': this.deckName, 'image': this.image}, this.$root.headers)
			.then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	console.log(response.data);
	                	this.$router.push('/admin/decks');
	                	
	                });
		},
		cancelDeck: function(deck) {
			this.deckName='';
			this.deckImage = new Image();
		}
	},
	components: {

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

img {
  width: 15%;
  margin: auto;
  display: block;
  margin-bottom: 10px;
}
</style>