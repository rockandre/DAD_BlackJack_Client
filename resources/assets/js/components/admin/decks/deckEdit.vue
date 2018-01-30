<template>
	<div>
		<div class="row">
			<div class="w-75 p-3">
				<div class="jumbotron">
					<h1>Editting Deck: {{this.deck.name}}</h1>
				</div>
			</div>
			<div class="w-25 p-3">
				<img class="img_hidden" :src="'/api/storage/'+this.deck.hidden_face_image_path">
			</div>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Suite</th>
					<th>Value</th>
					<th>Preview</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(card, index) in cards">
					<td>{{ card.suite }}</td>
					<td>{{ card.value }}</td>
					<td>
						<img class="card" :src="card.image" />
					</td>
					<td>
						<input type="file" class="form-control-file" @change="onFileChange" @click="this.currentCard = card.id" aria-describedby="fileHelp">
						<button v-if="card.image" @click="removeImage" class="btn btn-warning">Remove image</button>
					</td>
				</tr>
			</tbody>
		</table>	
	</div>
</template>

<script>
module.exports={
	props: ['deck'],
	data: function(){
		return { 
			image: undefined,
			currentCard: ''
		}
	}, 
	computed: {
		cards() {
			var suites = ["Club","Diamond","Heart","Spade"];
			var values = ["Ace", "2", "3", "4", "5", "6", "7", "8", "9", "10", "Jack", "Queen", "King"];
			let i = 0;
			var cards = {};
			for (var suite of suites) {
				for (var value of values){
					cards[i] = {id: i, suite: suite, value: value, image: undefined};
					i++;
				}
			}
			return cards;
		}
	},
	methods: {
		onFileChange(e) {
			console.log(e);
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
				this.cards[this.currentCard].image = e.target.result;
			};
			reader.readAsDataURL(file);
		},
		removeImage: function (e) {
			this.image = '';
		},
		changeCard(card) {
			this.currentCard = card;
		},
		saveDeck: function(){

		},
		cancelEdit: function(){

		},
		populateTable: function() {
			var suites = ["Club","Diamond","Heart","Spade"];
			var values = ["Ace", "2", "3", "4", "5", "6", "7", "8", "9", "10", "Jack", "Queen", "King"];
			let i = 0;
			for (var suite of suites) {
				for (var value of values){
					this.cards[i] = {suite:suite, value: value};
					i++;
				}
			}
		}
	},
	mounted() {
		this.populateTable();
	}
}
</script>

<style>

.img_hidden {
	width: 60%;
}

.card {
	width: 15%;
}

</style>