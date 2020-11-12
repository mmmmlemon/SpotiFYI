<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 fade_in_anim" width="20%;" v-bind:class="{ invisible: this.$store.state.homePage.loggedIn }">
                <h1>Site title</h1>
                <h4>A Laravel/Vue.js/Spotify Web API application</h4>
                <hr>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1024px-Laravel.svg.png" width="100px" alt="">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Vue.js_Logo_2.svg/1024px-Vue.js_Logo_2.svg.png" width="100px" alt="">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Spotify_logo_with_text.svg" width="100px" alt="">
                <hr>
                <br>
            </div>
            <div class="col-md-8" v-bind:class="{ invisible: !this.$store.state.homePage.loggedIn }">
                <h1 v-if="this.$store.state.homePage.spotifyUsername != false" class="fade_in_anim">Привет, <b>{{this.$store.state.homePage.spotifyUsername}}</b>!</h1>
                <div v-if="this.$store.state.homePage.spotifyUserTracksCount != -1" class="fade_in_anim">
                    <h3 v-if="this.$store.state.homePage.spotifyUserTracksCount >= 150">В твоей библиотеке более чем достаточно треков для анализа <i class="fas fa-heart" style="color:#1b77b9;"></i></h3>
                    <h4 v-else-if="this.$store.state.homePage.spotifyUserTracksCount >= 50">Нормалёк! 😉 В твоей библиотеке {{this.$store.state.homePage.spotifyUserTracksCount}} треков</h4>
                    <h4 v-else-if="this.$store.state.homePage.spotifyUserTracksCount >= 10">{{this.$store.state.homePage.spotifyUserTracksCount}} треков? Маловато будет! <img src="/img/malovato_budet.png" width="50px"></h4>  
                    <h4 v-else-if="this.$store.state.homePage.spotifyUserTracksCount < 10 && this.$store.state.homePage.spotifyUserTracksCount > 0">Что-то тут пусто, всего {{this.$store.state.homePage.spotifyUserTracksCount}} треков... 😳</h4>              
                    <h4 v-else-if="this.$store.state.homePage.spotifyUserTracksCount == 0">{{this.$store.state.homePage.spotifyUserTracksCount}} песен? bruh... <img src="/img/bruh.png" width="50px"></h4>
                    <h3 v-else></h3>

                    <h5 v-if="this.$store.state.homePage.spotifyUserTracksCount < 50">Слишком мало треков чтобы составить статистику. Добавь побольше песен в свою библиотеку (минимум: 50)</h5>
                    <h5 v-else class="fade_in_anim_500">Перейди в <router-link to="/spotify_profile">свой профиль</router-link> чтобы просмотреть статистику</h5>
                    <img src="https://www.cambridgemaths.org/Images/The-trouble-with-graphs.jpg" width="50%;" style="border-radius: 40px;" alt="">
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted(){
                console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'Welcome\' component mounted')
        },
    }

</script>