<template>
	<div>
		<div class="jumbotron">
			<h1>{{ title }}</h1>
		</div>

		<deck-list :decks="decks" @edit-click="editDeck" @delete-click="deleteDeck" @active-click="activeDeck" @message="childMessage" 
		ref="deckListRef"></deck-list>

		<div class="alert alert-success" v-if="showSuccess">
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
		<router-link to="/admin/decks/add" class="btn btn-primary mt-2">Add Deck</router-link>
		<edit-deck class="mt-5" :deck="currentDeck" @deck-saved="savedEditDeck" @deck-canceled="cancelEditDeck" v-if="currentDeck && action == 1"></edit-deck>		
	</div>				
</template>

<script type="text/javascript">
import DeckList from './deckList.vue';
import DeckEdit from './deckEdit.vue';

export default {
	data: function(){
		return { 
			title: 'List Deck',
			showSuccess: false,
			successMessage: '',
			currentDeck: null,
			action: 0,
			decks: []
		}
	},
	methods: {
		editDeck: function(deck){
			this.currentDeck = deck;
			this.action = 1;
			this.showSuccess = false;
		},
		activeDeck: function(deck) {
			this.action = 0;
			deck.active == 0 ? deck.active = 1 : deck.active = 0;
			axios.put('api/decks/'+deck.id, deck, this.$root.headers)
			.then(response => {
				this.showSuccess = true;
				this.successMessage = 'Decks Changed.';
				this.getDecks();
			});
		},
		deleteDeck: function(deck){
			this.action = 0;
			axios.delete('api/decks/'+deck.id, this.$root.headers)
			.then(response => {
				this.showSuccess = true;
				this.successMessage = 'Deck Deleted';
				this.getDecks();
			});
		},
		savedEditDeck: function(){
			this.currentDeck = null;
			this.action = 0;
			this.showSuccess = true;
			this.successMessage = 'Deck Saved';
		},
		cancelEditDeck: function() {
			this.currentDeck = null;
			this.action = 0;
		},
		getDecks: function(){
			axios.get('api/decks', this.$root.headers)
			.then(response=>{this.decks = response.data.data; });
		},
		childMessage: function(message){
			this.showSuccess = true;
			this.successMessage = message;
		}
	},
	components: {
		'deck-list': DeckList,
		'edit-deck': DeckEdit
	},
	mounted() {
		this.getDecks();
	}

}

</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>