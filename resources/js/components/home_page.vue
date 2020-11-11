<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8" width="20%;" v-bind:class="{ invisible: loggedIn }">
                <h1>Site title</h1>
                <h4>A Laravel/Vue.js/Spotify Web API application</h4>
                <hr>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1024px-Laravel.svg.png" width="100px" alt="">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Vue.js_Logo_2.svg/1024px-Vue.js_Logo_2.svg.png" width="100px" alt="">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Spotify_logo_with_text.svg" width="100px" alt="">
                <hr>
                <br>
            </div>
            <div class="col-md-8" v-bind:class="{ invisible: !loggedIn }">
                <h1 v-if="spotifyUsername != false" class="fade_in_anim">Привет, <b>{{spotifyUsername}}</b>!</h1>
                <div v-if="spotifyUserTracksCount != 0" class="fade_in_anim">
                    <h5>В твою библиотеку Spotify добавлено {{spotifyUserTracksCount}} треков <i class="fas fa-heart" style="color:#1b77b9;"></i></h5>
                    <h1 v-if="spotifyUserTracksCount >= 9000"> {{spotifyUserTracksCount}}?! Боже мой ಠ_ಠ </h1>
                    <h4 v-else-if="spotifyUserTracksCount > 3000">{{spotifyUserTracksCount}}? Ну ты капец 🤔</h4>
                    <h4 v-else-if="spotifyUserTracksCount >= 1000">Вау! Да ты меломан! 😍</h4>
                    <h4 v-else-if="spotifyUserTracksCount >= 500">Ого! Как много! 😳</h4>
                    <h4 v-else-if="spotifyUserTracksCount >= 200">Неплохо! 😏</h4>
                    <h4 v-else-if="spotifyUserTracksCount >= 50">Нормалёк! 😉</h4>
                    <h4 v-else-if="spotifyUserTracksCount >= 10">Маловато будет! <img src="/img/malovato_budet.png" width="50px"></h4>  
                    <h4 v-else-if="spotifyUserTracksCount < 10 && spotifyUserTracksCount > 0">Ничего не слышу, ничего не вижу</h4>              
                    <h4 v-else-if="spotifyUserTracksCount == 0">bruh... <img src="/img/bruh.png" width="50px"></h4>
                    <h3 v-else></h3>

                    <h5 v-if="spotifyUserTracksCount < 10">Слишком мало треков чтобы составить статистику. Добавь побольше песен в свою библиотеку.</h5>
                    <h5 v-else class="fade_in_anim_500">Перейди в <router-link to="/spotify_profile">Мой Профиль</router-link> чтобы просмотреть свою статистику</h5>
                    <img src="https://www.cambridgemaths.org/Images/The-trouble-with-graphs.jpg" width="50%;" style="border-radius: 40px;" alt="">
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                loggedIn: false,
                spotifyUsername: false,
                spotifyUserTracksCount: 0
            }
        },
        mounted() {
            console.log('\'home_page\' component mounted')
        },

        created(){
            let uri_username = '/api/get_spotify_username';
            this.axios.get(uri_username).then((response) => {
                this.loggedIn = response.data.loggedIn;
                if(response.data.spotifyUsername != undefined)
                {
                    this.spotifyUsername = response.data.spotifyUsername;
                }
                else
                {
                    this.loggedIn = false;
                }
            })

           let uri = '/api/get_spotify_tracks_count';
           this.axios.get(uri).then((response) => {
               this.spotifyUserTracksCount = response.data;
           });
        }
    }
</script>