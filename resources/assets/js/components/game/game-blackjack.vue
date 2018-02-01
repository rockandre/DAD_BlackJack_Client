<template>
	<div class="gameseparator">
        <div>
            <h2 class="text-center">Game {{ game.gameID }}</h2>
            <br>
        </div>
        <div class="game-zone-content">       
            <div class="alert" :class="alerttype">
                <h4>{{ message }}</h4>
                <a href="#" v-on:click.prevent="leaveGame()" v-if="!this.game.gameStarted">Leave Game</a>
                <a href="#" v-on:click.prevent="closeGame()" v-if="this.game.gameEnded">Close Game</a>
            </div>
            <div id="buttonsArea" class="btn-group" role="group" align="text-center">
                <button class="btn btn-s btn-success btn-secundary" v-on:click="startGame()" v-if="ownPlayerNumber==0 && this.game.gameCanBeStarted && !this.game.gameStarted">START GAME</button>
                <button class="btn btn-s btn-primary btn-secundary" v-on:click="clickAction(hit)" v-if="!playerAction && this.game.gameStarted">HIT</button>
                <button class="btn btn-s btn-danger btn-secundary" v-on:click="clickAction(stand)" v-if="!playerAction && this.game.gameStarted">STAND</button>
            </div>
            <div class="board">
                <div class="row">
                    <div class="col-4">
                    </div>    
                    <div class="col-4" v-if="game.playerList[0] != undefined">
                        <h3 :class="game.playerList[0].name == currentPlayer ? 'text-primary' : ''">{{ game.playerList[0].name }}</h3>

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
                        <h3 :class="game.playerList[1].name == currentPlayer ? 'text-primary' : ''">{{ game.playerList[1].name }}</h3>

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
                        <h3 :class="game.playerList[2].name == currentPlayer ? 'text-primary' : ''">{{ game.playerList[2].name }}</h3>

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
                        <h3 :class="game.playerList[3].name == currentPlayer ? 'text-primary' : ''">{{ game.playerList[3].name }}</h3>

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
            myHand: [],
            playerAction: false
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
            return this.$root.user.nickname;
        },
        numberOfPlayers(){
            return this.game.playerList.length;
        },
        message(){
            if(!this.game.gameStarted){
                if(this.game.playerList.length<2) {
                    return "Waiting for Players To Join";
                } else {
                    if(this.ownPlayerNumber == 0) {
                        return "Ready to Start Game";
                    } else {
                        return "Waiting for Game Owner to start the Game!";
                    }
                }
            } else if(this.game.gameEnded) {
                if(this.game.winners.length == 1) {
                    if(this.game.winners[0].name == this.$root.user.nickname) {
                        return "Congratulations you WIN !!!!";
                    } else {
                        return this.game.winners[0].name+" win! You lose!!";
                    }
                } else {
                    let winnersString = '';
                    this.game.winners.forEach((player, index)=> {
                        winnersString += player.name;
                        if(index < (this.game.winners.length-1)){
                            winnersString += " / ";
                        }
                    });
                    return "The game ended in a Tie! Players: "+winnersString;
                }
            } else {
                if(!this.playerAction && this.game.playerList[this.ownPlayerNumber].stand == 0){
                    return "Select an action!";
                } else {
                    if(this.game.playerList[this.ownPlayerNumber].stand == 1) {
                        return "Other Players are finishing the game!";
                    } else {
                        return "Wait for others Players";
                    }
                }
            }
        },
        alerttype(){
            if(!this.game.gameStarted){
                if(this.game.playerList.length<2) {
                    return "alert-warning";
                } else {
                    if(this.ownPlayerNumber == 0) {
                        return "alert-success";
                    } else {
                        return "alert-success";
                    }
                }
            } else if(this.game.gameEnded) {
                if(this.game.winners.length == 1) {
                    if(this.game.winners[0].name == this.$root.user.nickname) {
                        return "alert-success";
                    } else {
                        return "alert-danger";
                    }
                } else {
                    return "alert-warning";
                }
            } else {
                if(!this.playerAction){
                    return "alert-info";
                } else {
                    if(this.game.playerList[this.ownPlayerNumber].stand == 1) {
                        return "alert-success";
                    } else {
                        return "alert-warning";
                    }
                }
            }
        },
    },
    sockets:{
        my_hand_changed(data){
            if(data.gameID == this.game.gameID){
                this.playerAction = false;
                this.myHand.push(data.hand[data.hand.length-1]);
            }
            this.playerAction = false;
            if(this.handSum() >= 21) {
                this.playerAction = true;
            }
        }
    },
    methods: {
        cardImageURL(cardid) {
            var imgSrc = String(cardid);
            return '/api/storage/'+ this.game.deck.name + "/" + imgSrc + '.png';
        },
        clickAction(action){
            if(!this.game.gameEnded){
                this.playerAction = true;
                if(action == this.hit){
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
                this.playerAction = false;
                this.$emit('startgame', this.game);
            }

        },
        calcOwnPlayerNumber(){
            var i=0;
            var finali = -1;
            this.game.playerList.forEach(element => {
                if(this.currentPlayer == element.name){
                    finali = i;
                }
                i++;
            });
            return finali;
        },
        handSum() {
            let sum = 0;
            this.myHand.forEach(card => {
                sum += card.value;
            })
            return sum;
        },
        leaveGame() {
            this.$emit('leavegame', this.game);
        },
        closeGame() {
            this.$emit('closegame');
        },

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
