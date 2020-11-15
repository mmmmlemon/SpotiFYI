<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 fade_in_anim" width="20%;" v-bind:class="{ invisible: loggedIn }">
                <h1>Site title</h1>
                <h4>A Laravel/Vue.js/Spotify Web API application</h4>
                <hr>
                <br>
            </div>
            <div class="col-md-8" v-bind:class="{ invisible: !loggedIn }">
                <h1 v-if="spotifyUsername != false" class="fade_in_anim">Привет, <b>{{spotifyUsername}}</b>!</h1>
                <div class="container bounce_in_anim" v-if="spotifyUserTracksCount == -1 && loggedIn == true">
                    <Loader/>
                </div>
                <div v-if="spotifyUserTracksCount != -1" class="fade_in_anim">
                    <h3 v-if="spotifyUserTracksCount >= 150">
                        В твоей библиотеке более чем достаточно треков для анализа <i class="fas fa-heart primary_color heartbeat_anim"></i>
                    </h3>

                    <h4 v-else-if="spotifyUserTracksCount >= 50">
                        В твоей библиотеке достаточно треков для анализа! 😉
                    </h4>
                    <h4 v-else-if="spotifyUserTracksCount >= 10">
                        Ай! Маловато будет! <img src="/img/malovato_budet.png" width="50px">
                    </h4>  
                    <h4 v-else-if="spotifyUserTracksCount < 10 && spotifyUserTracksCount > 0">
                        Ой, что-то тут пусто... 😳
                    </h4>              
                    <h4 v-else-if="spotifyUserTracksCount == 0">
                        {{spotifyUserTracksCount}} песен? bruh... <img src="/img/bruh.png" width="50px">
                    </h4>
                    <h3 v-else></h3>

                    <h5 v-if="spotifyUserTracksCount < 50 && spotifyUserTracksCount > 0">
                        Слишком мало треков чтобы составить статистику. Добавь побольше песен в свою библиотеку (в библиотеке: {{spotifyUserTracksCount}}, нужно: 50).
                    </h5>
                    <h5 v-else-if="spotifyUserTracksCount == 0">
                       Ни одной песни в библиотеке. Добавь их побольше (нужно: 50).
                    </h5>
                    <h5 v-else class="fade_in_anim_500">
                        Перейди в <router-link to="/profile">свой профиль</router-link> чтобы просмотреть статистику
                    </h5>
                    <img class="fade_in_anim" src="https://www.cambridgemaths.org/Images/The-trouble-with-graphs.jpg" width="50%;" style="border-radius: 40px;" alt="">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            loggedIn: function(){
                return this.$store.state.homePage.loggedIn;
            },
            spotifyUsername: function(){
                return this.$store.state.homePage.spotifyUsername;
            },
            spotifyUserTracksCount: function(){
                return this.$store.state.homePage.spotifyUserTracksCount;
            },
            trackWord: function(){
                return this.spotifyUserTracksCount;
            }
        },
        mounted(){
                console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'Welcome\' component mounted')
        },
    }
</script>