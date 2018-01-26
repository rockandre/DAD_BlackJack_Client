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
                <div class="row">
                    <div class="col-4">
                    </div>    
                    <div class="col-4" v-if="game.playerList[0] != undefined">
                        <h3>{{ game.playerList[0].name }}</h3>

                        <div v-if="game.playerList[0].name != currentPlayer">
                            <img v-for="(card) in game.playerList[0].pubHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                        <div v-else>
                            <img v-for="(card) in myHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)"width="70" height="100" >
                        </div>
                    </div>
                    <div class="col-4" v-else>
                    </div>
                    <div class="col-4">
                    </div>
                </div>
                <div class="row">

                    <div class="col-4" v-if="game.playerList[1] != undefined">
                        <h3>{{ game.playerList[1].name }}</h3>

                        <div v-if="game.playerList[1].name != currentPlayer">
                            <img v-for="(card) in game.playerList[1].pubHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                        <div v-else>
                            <img v-for="(card) in myHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                    </div>
                    <div class="col-4" v-else>
                    </div>
                    <div class="col-4">

                    </div>
                    <div class="col-4" v-if="game.playerList[2] != undefined">
                        <h3>{{ game.playerList[2].name }}</h3>

                        <div v-if="game.playerList[2].name != currentPlayer">
                            <img v-for="(card) in game.playerList[2].pubHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                        <div v-else>
                            <img v-for="(card) in myHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                    </div>
                    <div class="col-4" v-else>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                    </div>
                    <div class="col-4" v-if="game.playerList[3] != undefined">
                        <h3>{{ game.playerList[3].name }}</h3>

                        <div v-if="game.playerList[3].name != currentPlayer">
                            <img v-for="(card) in game.playerList[3].pubHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                        <div v-else>
                            <img v-for="(card) in myHand" v-bind:card="card" v-bind:key="card.id" v-bind:src="cardImageURL(card.id)" width="70" height="100">
                        </div>
                    </div>
                    <div class="col-4" v-else>
                    </div>
                    <div class="col-4">
                    </div>
                </div>
            </div>
            <div id="buttonsArea">
                <div id="creatorArea">
                    <button class="btn btn-s btn-success" v-on:click="startGame()">START GAME</button>
                </div>
                <div id="playActions">
                    <button class="btn btn-s btn-primary" v-on:click="clickAction(hit)">HIT</button>
                    <button class="btn btn-s btn-danger" v-on:click="clickAction(stand)">STAND</button>
                </div>
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
            ownPlayerNumber: 0,
            myHand: []
        }
    },
    watch: {
        // whenever socketID changes, this function will run
        socketID: function () {
            this.ownPlayerNumber = this.calcOwnPlayerNumber();
        }
    },
    computed: {
        currentPlayer(){
            //return this.user.nickname;
            return this.$root.user.nickname;
        },
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
    sockets:{
        my_hand_changed(data){
            if(data.gameID == this.game.gameID){
                this.myHand.push(data.hand[data.hand.length-1]);
            }
        }
    },
    methods: {
        cardImageURL(cardid) {
            var imgSrc = String(cardid);
            return 'img/baralho'+ this.baralhoImgID + "/" + imgSrc + '.png';
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
            } else {
                console.log("Game already ended");
            }
        },
        startGame(){
            if(this.game.gameCanBeStarted){
                this.$emit('startgame', this.game);
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