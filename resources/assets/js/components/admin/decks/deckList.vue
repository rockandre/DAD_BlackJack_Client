<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>active</th>
				<th>complete</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="deck in decks"  :key="deck.id" :class="{activerow: editingDeck === deck}">
				<td>{{ deck.name }}</td>
				<td>{{ deck.active ? 'Yes' : 'No'}}</td>
				<td>{{ deck.complete ? 'Yes' : 'No'}}</td>
				<td class="w-25 p-3">
					<div class="btn-group col-12" role="group" aria-label="Basic example">
						<a class="btn btn-xs btn-primary" v-on:click.prevent="editDeck(deck)">Manage Cards</a>
						<a class="btn btn-xs btn-warning" v-on:click.prevent="activeDeck(deck)" v-if="!deck.active && deck.complete">Activate</a>
						<a class="btn btn-xs btn-warning" v-on:click.prevent="activeDeck(deck)" v-if="deck.active && deck.complete">Desactivate</a>
						<a class="btn btn-xs btn-danger" v-on:click.prevent="deleteDeck(deck)">Delete</a>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script type="text/javascript">
// Component code (not registered)
module.exports={
	props: ['decks'],
	data: function(){
		return { 
			editingDeck: null
		}
	},
	methods: {
		editDeck: function(deck) {
			this.editingDeck = deck;
			this.$emit('edit-click', deck);
		},		
		deleteDeck: function(deck) {
			this.editingDeck = null;
			this.$emit('delete-click', deck);
		},
		activeDeck: function(deck) {
			this.editingDeck = null;
			this.$emit('active-click', deck);
		}
	},		
}
</script>

<style scoped>
tr.activerow {
	background: #123456  !important;
	color: #fff          !important;
}
td
.table td {
	border-top: 0px !important;
}

</style>

