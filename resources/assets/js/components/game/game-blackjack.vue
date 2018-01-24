<template>
	<div class="gameseparator">
        <div>
            <h2 class="text-center">Game {{ game.gameID }}</h2>
            <br>
        </div>
        <div class="game-zone-content">       
            <div class="alert" :class="alerttype">
                <strong>{{ message }} &nbsp;&nbsp;&nbsp;&nbsp;<a v-on:click.prevent="closeGame">Close Game</a></strong>
            </div>
            <div class="board">
                <div v-for="(player, key) of game.playerList" >
                    <h3>{{ player.name }}</h3>
                    <div v-for="(card, key) of player.pubHand" >
                        <img v-bind:src="cardImageURL(card.id)">
                    </div>
                </div>
            </div>
            <div id="playActions">
                <button class="btn btn-s btn-primary" v-on:click="clickAction(hit)">HIT</button>
                <button class="btn btn-s btn-danger" v-on:click="clickAction(hit)">STAND</button>
            </div>
            <hr>
        </div>  
    </div>			
</template>

<script type="text/javascript">
	export default {
        props: ['game'],
        data: function(){
			return {
                baralhoImgID: 1,
            }
        },
        computed: {
            ownPlayerNumber(){
                let i=0;
                game.playerSocketList.forEach(element => {
                    if(this.$parent.socketId == element){
                        return i;
                    }
                    i++;
                });
                return -1;
            },
            message(){
                // return Message to show"field4": "There was a Tsunami on Paris"
                if(!this.game.gameStarted){
                    return "Game not started yet";
                } else if(this.game.gameEnded){
                    if(this.game.winner == this.ownPlayerNumber){
                        return "Game has ended. You won :D";
                    } else if(this.game.winner == 0){
                        return "Game has ended. It's a tie!";
                    }
                    return "Game has ended. You lost :( " + this.adversaryPlayerName + " has won.";
                } else {
                    if(this.game.playerTurn == this.ownPlayerNumber){
                        return "It's your turn";
                    } else {
                        return "It's " + this.adversaryPlayerName + "'s turn";
                    }
                }
                return "Game is insconsistent";
            },
            alerttype(){
                if(!this.game.gameStarted){
                    return "alert-warning";
                } else if (this.game.gameEnded){
                    if(this.game.winner == this.ownPlayerNumber){
                        return "alert-success";
                    } else if(this.game.winner == 0){
                        return "alert-info";
                    }
                    return "alert-danger";
                }  else if(this.game.playerTurn == this.ownPlayerNumber){
                        return "alert-success";
                    } else {
                        return "alert-warning";
                    }
            },
        },
        methods: {
            cardImageURL: function (cardid) {
                var imgSrc = String(cardid);
                return 'img/baralho'+ baralhoImgID + "/" + imgSrc + '.png';
            },
            clickAction(action){
                if(!this.game.gameEnded){
                    if(this.game.playerTurn != this.ownPlayerNumber){
                        alert("You can't play yet!");
                    } else {
                        if(this.game.board[index] == 0){
                            this.$emit('clickaction', this.game, index);
                            //this.$parent.play(this.game, index);
                        }
                    }
                }
            },
            closeGame(){
                this.$parent.close(this.game);
            }
        }
    }
</script>

<style scoped>	
.gameseparator{
    border-style: solid;
    border-width: 2px 0 0 0;
    border-color: black;
}
</style>