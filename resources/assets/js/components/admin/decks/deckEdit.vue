<template>
	<div>
		<div class="row">
			<div class="w-75 p-3">
				<div class="jumbotron">
					<h1>Editting Deck: {{this.deckAux.name}}</h1>
				</div>
			</div>
			<div class="w-25 p-3">
				<img class="img_hidden" :src="'/api/storage/'+this.deckAux.hidden_face_image_path">
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
				<tr v-for="(card, index) in deckAux.cards">
					<td>{{ card.suite }}</td>
					<td>{{ card.value }}</td>
					<td>
						<img class="card" v-if="card.image && card.image!='REMOVED'" :src="card.image" />
					</td>
					<td>
						<input v-if="!card.image || card.image=='REMOVED'" type="file" class="form-control-file " @change="onFileChange" @click="setCurrentCard(index)" aria-describedby="fileHelp">
						<button v-if="card.image && card.image!='REMOVED'" @click="removeImage(index)" class="btn btn-warning">Remove image</button>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="form-group">
			<a class="btn btn-primary" v-on:click.prevent="saveCards">Save</a>
			<a class="btn btn-danger" v-on:click.prevent="cancelCards()">Cancel</a>
		</div>	
	</div>
</template>

<script>
import Card from '../../../classes/card.js';
import Deck from '../../../classes/deck.js';
export default {
	props: ['deck'],
	data: function(){
		return { 
			image: '',
			currentCard: 0,
			deckAux: new Deck(),
			cardsWithImage: []

		}
	}, 
	methods: {
		getCardsWithImage: function () {
			this.cardsWithImage = [];
			for (var card of this.deckAux.cards) {
				if (card.image != '' ) {
					this.cardsWithImage.push(card);
				}
			}
		},
		saveCards: function(){
			this.getCardsWithImage();
			axios.post('api/card', {'deckId': this.deck.id,'deckName': this.deck.name, 'cards': this.deckAux.cards}, this.$root.headers)
			.then(response => {
				this.deck.complete = response.data.complete;
				this.deckAux.complete = response.data.complete;
				this.$emit('deck-saved', this.deck);
			});
		},
		cancelCards: function(){
			this.$emit('deck-canceled', this.deck);
		},
		setCurrentCard(card) {
			this.currentCard=card;
		},
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
				vm.deckAux.cards[vm.currentCard].image = e.target.result;
			};
			reader.readAsDataURL(file);

		},
		removeImage: function (currentCard) {
			this.deckAux.cards[currentCard].image = 'REMOVED';
		},
		saveDeck: function(){

		},
		cancelEdit: function(){

		},

		populateCards(callback) {
			var suites = ["Club","Diamond","Heart","Spade"];
			var values = ["Ace", "2", "3", "4", "5", "6", "7", "8", "9", "10", "Jack", "Queen", "King"];
			let i = 0;
			let cards = [];
			for (var suite of suites) {
				for (var value of values){
					let exists = 0;
					let cardId = 0;
					var image = new Image();
					this.deck.cards.forEach(card => {
						if(card.value == value && card.suite == suite) {
							image.src = '/api/storage/'+card.path;
							exists = 1;
							cardId = card.id;
						}
					});

					if(exists) {
						cards[i] = new Card(cardId, this.deckAux.id, image.src, suite, value);
					} else {
						cards[i] = new Card(i, this.deckAux.id, '', suite, value);
					}

					console.log(""+i);
					if(i == 51) {
						callback(cards);
					}
					i++;
				}
			}
		}
	},
	mounted() {
		this.deckAux.parse(this.deck);
		let vm = this;
		this.populateCards(function(cardsAux) {
			vm.deckAux.cards = cardsAux;
		});
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