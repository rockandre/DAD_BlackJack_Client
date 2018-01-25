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
                <div v-for="(player) in game.playerList" v-bind:player="player" v-bind:key="player.name" >
                    <h3>{{ player.name }}</h3>
                    <div v-for="(card) in player.pubHand" v-bind:card="card" v-bind:key="card.id" >
                        <img v-bind:src="cardImageURL(card.id)">
                    </div>
                </div>
            </div>
            <div id="playActions">
                <button class="btn btn-s btn-primary" v-on:click="clickAction(hit)">HIT</button>
                <button class="btn btn-s btn-danger" v-on:click="clickAction(stand)">STAND</button>
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
                hit: 1,
                stand: 0,
                socketID: "",
                ownPlayerNumber: 0
            }
        },
        watch: {
            // whenever socketID changes, this function will run
            socketID: function () {
                console.log("Entrou no watch");
                console.log(this.calcOwnPlayerNumber());
                console.log(this.socketID);
                this.ownPlayerNumber = this.calcOwnPlayerNumber();
            }
        },
        computed: {
            numberOfPlayers(){
                return this.game.playerList.length;
            },
            message(){
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
                    if(action == this.hit){
                        console.log("Hit "+this.hit);
                        if(this.game.playerList[this.ownPlayerNumber].stand==0 && this.game.playerList[this.ownPlayerNumber].pubHand.length<4){
                            this.$emit('clickaction', this.game, this.hit);
                        } else {
                            alert("You cant hit anymore.");
                        }
                    } else {
                        console.log("Stand "+this.stand);
                        this.$emit('clickaction', this.game, this.stand);
                    }
                }
            },
            closeGame(){
                this.$parent.close(this.game);
            },
            calcOwnPlayerNumber(){
                var i=0;
                var finali = -1
                this.game.playerSocketList.forEach(element => {
                    if( this.socketID == element){
                        finali = i;
                    }
                    i++;
                });
                return finali;
            }
        },
        mounted(){
            this.socketID = this.$socket.id;
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