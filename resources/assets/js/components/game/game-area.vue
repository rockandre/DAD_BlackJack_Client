<template>
    <div>
        <div class="padding-top">
            <h3 class="text-center">Lobby</h3>
            <p><button class="btn btn-xs btn-success" v-on:click.prevent="createGame">Create a New Game</button></p>
            <hr>
            <h4>Pending games (<a href="#" @click.prevent="loadLobby">Refresh</a>)</h4>
            <lobby :games="lobbyGames" @join-click="join" @resume-click="resume"></lobby>
            <template v-for="game in activeGames" :game="game">
                <game :game="game" @startgame="start" @clickaction="play" :key="game.gameID" @leavegame="leave" @closegame="close"></game>
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
import Lobby from './gamelobby.vue';
import GameBlackJack from './game-blackjack.vue';

export default {
    data: function(){
        return {
            title: 'BlackJack',
            lobbyGames: [],
            activeGames: [],
        }
    },
    beforeRouteEnter (to, from, next) {
        setTimeout(function(){ 
            next();
        }, 3000);
    },
    computed: {
        currentPlayer(){
            return this.$root.user.nickname;
        },
        socketId(){
            return this.$socket.id;
        }
    },
    sockets:{
        connect(){
            console.log('Socket Connected');
        },
        discconnect(){
            console.log('Socket Disconnected');
            this.socketId = "";
        },
        lobby_changed(){
            // For this to work, websocket server must emit a message
            // named "lobby_changed"
            this.loadLobby();
        },
        my_active_games_changed(){
            this.loadActiveGames();
        },
        my_activegames(games){
            this.activeGames = games;
        },
        my_lobbygames(games){
            this.lobbyGames = games;
        },
        game_changed(game){
            for (var lobbyGame of this.lobbyGames){
                if(game.gameID == lobbyGame.gameID){
                    Object.assign(lobbyGame, game);
                    break;
                }
            }
            for (var activeGame of this.activeGames){
                if(game.gameID == activeGame.gameID){
                    Object.assign(activeGame, game);
                    break;
                }
            }
        }
    },
    methods: {
        loadLobby(){
            /// send message to server to load the list of games on the lobby
            this.$socket.emit('get_my_lobbygames');
        },
        loadActiveGames(){
            /// send message to server to load the list of games that player is playing
            this.$socket.emit('get_my_activegames', { nickname: this.currentPlayer });
        },
        createGame(){
            // For this to work, server must handle (on event) the "create_game" message
            if (this.currentPlayer == "") {
                alert('Current Player is Empty - Cannot Create a Game');
                return;
            }
            else {
                this.$socket.emit('create_game', { playerName: this.currentPlayer });   
            }
        },
        join(game){
            // Click to join game
            game.playerList.forEach(element => {
                if(element.name == this.currentPlayer){
                    alert('You already joined the game');
                }
            });
            this.$socket.emit('join_game', {gameID: game.gameID, playerName: this.currentPlayer});
        },
        resume(game) {
            this.loadActiveGames();
        },
        play(game, action){
            // play a game - Sends user action
            this.$socket.emit('play', {gameID: game.gameID, action: action});
        },
        start(game){
            this.$socket.emit('start_game', {gameID: game.gameID});
        },
        close(game) {
            this.$socket.emit('remove_game');
        },
        leave(game) {
            this.$socket.emit('leave_game', {gameID: game.gameID});
        }
    },
    components: {
        'lobby': Lobby,
        'game': GameBlackJack,
    },
    mounted() {
        this.loadLobby();
        this.loadActiveGames();
    }

}
</script>

<style>	
.padding-top {
    padding-top: 55px;
}

</style>