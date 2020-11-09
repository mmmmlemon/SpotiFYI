<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="logged_in == false" class="col-md-8">
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
                <h1>Привет, <b>{{spotify_username}}</b>!</h1>
                <h5>В твою библиотеку Spotify добавлено {{spotify_track_count}} треков <i class="fas fa-heart"></i></h5>
                <h4 v-if="spotify_track_count > 10">Ого! Как много!</h4>
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
                logged_in: false,
                spotify_username: "",
                spotify_track_count: 0
            }
        },
        mounted() {
            console.log('\'home_page\' component mounted')
        },

        created(){
           let uri = '/api/home_page';
           this.axios.get(uri).then((response) => {
               this.logged_in = response.data.logged_in;
               this.spotify_username = response.data.spotify_username;
               this.spotify_track_count = response.data.spotify_user_tracks.length;

           });
        }
    }
</script>