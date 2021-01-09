// Welcome
<template>

    <div class="container">
        <div class="top10_image_card" :style="{backgroundImage: `url('https://www.study.ru/uploads/server/4yTwWERsmPY6NnAO.jpg')`}">
        </div>
        <div class="row justify-content-center fade_in_anim_500">
            <!-- если пользователь не залогинен -->
            <div class="col-12 col-sm-12 col-md-10 col-lg-10 padding_10 margin_vertical" width="20%;" v-if="spotifyUsername == false">
               
                <div class="col-12">
                    <h2 class="text-center"><b>SpotiFYI</b></h2>
                    <div class="text-center">
                        <img src="/logo.png" width="10%" alt="">
                    </div>
                    <h5 class="text-center border_underline">Какой-нибудь крутой слоган</h5>
                    <p>Какое-нибудь крутое описание сайта. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veritatis tenetur cum quaerat eveniet suscipit minus ipsum natus totam porro vero officiis odit est, rem alias minima sed, officia delectus quisquam.Sit nihil, dignissimos est aperiam molestias voluptatum perferendis ad quaerat laudantium odio sequi, vero eius. Doloribus quibusdam unde, enim voluptas assumenda, maxime id distinctio vero quo ullam neque necessitatibus aspernatur!</p>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-10 margin_vertical justify-content-center">
                            <a href="/login" class="btn btn-primary btn-rounded btn-block">Войти в Spotify</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- если пользователь залогинен -->
            <div class="col-12 col-sm-12 col-md-10 col-lg-10" v-if="spotifyUsername != -1 && spotifyUsername != false">

                <div class="row justify-content-center text-center">
                    <!-- приветствие для больших экранов -->
                    <div class="col-11 text-center d-none d-md-block">
                        <h2 v-if="spotifyUsername != false" class="fade_in_anim font_4vw">Привет, <b>{{spotifyUsername}}</b>!</h2>
                    </div>
                    <!-- для мобилок -->
                    <div class="col-11 text-center d-sm-block d-md-none">
                        <h2 v-if="spotifyUsername != false" class="fade_in_anim font_6vw">Привет, <b>{{spotifyUsername}}</b>!</h2>
                    </div>
                    
                    <!-- лоадер -->
                    <div class="container bounce_in_anim" v-if="spotifyUserTracksCount == -1 && spotifyUsername != false">
                        <Loader/>
                    </div>

                    <!-- когда загрузится кол-во треков, показываем сообщение -->
                    <div v-if="spotifyUserTracksCount != -1" class=" col-8 fade_in_anim">
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
                            Ой, что-то у тебя пусто... 😳
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
                            Перейди в <router-link to="/profile" class="border_underline">свой профиль</router-link> чтобы просмотреть статистику
                        </h5>
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <img class="fade_in_anim" src="https://www.cambridgemaths.org/Images/The-trouble-with-graphs.jpg" width="90%" style="border-radius: 40px;" alt="">
                            </div>
                        </div> 
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
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
    }
</script>