<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="loggedIn == false" class="col-md-8">
                <h1>Site title</h1>
                <h4>A Laravel/Vue.js/Spotify Web API application</h4>
                <hr>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1024px-Laravel.svg.png" width="100px" alt="">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Vue.js_Logo_2.svg/1024px-Vue.js_Logo_2.svg.png" width="100px" alt="">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Spotify_logo_with_text.svg" width="100px" alt="">
                <hr>
                <br>
            </div>
            <div v-else class="col-md-8">
                <h1>Привет, <b>{{spotifyUsername}}</b>!</h1>
                <h5>В твою библиотеку Spotify добавлено {{spotifyTrackCount}} треков <i class="fas fa-heart" style="color: 	#1b77b9;"></i></h5>
                <h4 v-if="spotifyTrackCount > 10">Ого! Как много!</h4>
                <h3 v-else>Маловато будет!</h3>
                <h5>Перейди в <router-link to="/spotify_profile">Мой Профиль</router-link> чтобы просмотреть свою статистику</h5>
                <img src="https://www.cambridgemaths.org/Images/The-trouble-with-graphs.jpg" style="border-radius: 40px;" alt="">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                loggedIn: false,
                spotifyUsername: "",
                spotifyTrackCount: 0
            }
        },
        mounted() {
            console.log('\'home_page\' component mounted')
        },

        created(){
           let uri = '/api/home_page';
           this.axios.get(uri).then((response) => {
               this.loggedIn = response.data.loggedIn;
               this.spotifyUsername = response.data.spotifyUsername;
               this.spotifyTrackCount = response.data.spotifyUserTracks.length;

           });
        }
    }
</script>