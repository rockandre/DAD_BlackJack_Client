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
			<input type="file" class="form-control-file" id="image" @change="processFile($event)" aria-describedby="fileHelp">
			<small id="fileHelp" class="form-text text-muted">Insert here the image of the hidden face of your deck.</small>
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
			deckImage: ''
		}
	},
	methods: {
		processFile(event) {
			this.deckImage = event.target.files[0]
		},
		addDeck: function(deck){
			axios.put('api/deck', this.$root.headers)
			.then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.deckName, response.data.data.name);
	                	Object.assign(this.deckImage, response.data.data.image);
	                	this.$emit('deck-added');
	                });
			console.log(response.data.data.name);
		},
		cancelDeck: function(deck) {
			this.deckName='';
			this.deckImage='';
		}
	},
	components: {

	},
	mounted() {
	}

}

$('input[type=file]').change(function () {
	console.log(this.files[0].mozFullPath);
});

</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>