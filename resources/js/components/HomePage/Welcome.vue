// Welcome
<template>
    <div class="container fadeInAnim">
        <!-- фоновая картинка -->
        <BackgroundImage :backgroundImageUrl="homePageImageUrl"/>
        <div class="row justify-content-center">
            <!-- если пользователь не залогинен -->
            <div class="col-12 col-sm-12 col-md-10 col-lg-10 paddingSides marginVertical" width="20%;" v-if="spotifyUsername == false">
                <div class="col-12">
                    <h2 class="text-center"><b>SpotiFYI</b></h2>
                    <div class="text-center ">
                        <img :src="siteLogoUrl" class="fadeInAnim" width="10%" alt="">
                    </div>
                    <h5 class="text-center borderUnderline">Какой-нибудь крутой слоган</h5>
                    <p>Какое-нибудь крутое описание сайта. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veritatis tenetur cum quaerat eveniet suscipit minus ipsum natus totam porro vero officiis odit est, rem alias minima sed, officia delectus quisquam.Sit nihil, dignissimos est aperiam molestias voluptatum perferendis ad quaerat laudantium odio sequi, vero eius. Doloribus quibusdam unde, enim voluptas assumenda, maxime id distinctio vero quo ullam neque necessitatibus aspernatur!</p>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-10 justify-content-center marginVertical">
                            <a href="/spotify_login" class="btn btn-primary btn-rounded btn-block">Войти в Spotify</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- если пользователь залогинен -->
            <div class="col-12 col-sm-12 col-md-10 col-lg-10" v-if="spotifyUsername != -1 && spotifyUsername != false">
                <div class="row justify-content-center">
                    <!-- приветствие для больших экранов -->
                    <div class="col-11 text-center d-none d-md-block fadeInAnim">
                        <h2 v-if="spotifyUsername != false" class="font4vw">Привет, <b>{{spotifyUsername}}</b>!</h2>
                    </div>
                    <!-- для мобилок -->
                    <div class="col-11 text-center d-sm-block d-md-none fadeInAnim">
                        <h2 v-if="spotifyUsername != false" class="font6vw">Привет, <b>{{spotifyUsername}}</b>!</h2>
                    </div>
                    
                    <!-- лоадер -->
                    <div class="container bounceInAnim" v-if="spotifyUserTracksCount == -1 && spotifyUsername != false">
                        <Loader/>
                    </div>

                    <!-- когда загрузится кол-во треков, показываем сообщение -->
                    <div v-if="spotifyUserTracksCount != -1" class="col-10 fadeInAnim">
                        <!-- если треков больше 150 -->
                        <h3 v-if="spotifyUserTracksCount >= 150">
                            В твоей библиотеке более чем достаточно треков для анализа <i class="fas fa-heart primaryColor heartbeatAnim"></i>
                        </h3>
                        <!-- если треков больше или равно 50 -->
                        <h4 v-else-if="spotifyUserTracksCount >= 50">
                            В твоей библиотеке достаточно треков для анализа! 😉
                        </h4>
                        <!-- если треков больше или равно 10 -->
                        <h4 v-else-if="spotifyUserTracksCount >= 10">
                            Ай! Маловато будет! 🤔
                        </h4>  
                        <!-- если треков меньше 10 -->
                        <h4 v-else-if="spotifyUserTracksCount < 10 && spotifyUserTracksCount > 0">
                            Ой, что-то у тебя пусто...😳
                        </h4>              
                        <!-- если треков 0 -->
                        <h4 v-else-if="spotifyUserTracksCount == 0">
                            {{spotifyUserTracksCount}} песен? bruh... 💩
                        </h4>
                        <h3 v-else></h3>
                        
                        <!-- сообщение если кол-во треков больше нуля, но меньше 50 -->
                        <h5 v-if="spotifyUserTracksCount < 50 && spotifyUserTracksCount > 0">
                            Слишком мало треков чтобы составить статистику. Добавь побольше песен в свою библиотеку (в библиотеке: {{spotifyUserTracksCount}}, нужно: 50).
                        </h5>
                        <!-- сообщение если треков - ноль -->
                        <h5 v-else-if="spotifyUserTracksCount == 0">
                            Ни одной песни в библиотеке. Добавь их побольше (нужно: 50).
                        </h5>
                        <!-- ссылка на профиль -->
                        <h5 v-else class="fadeInAnimSlow">
                            Перейди в <router-link to="/profile" class="borderUnderline">свой профиль</router-link> чтобы просмотреть статистику
                        </h5>
                        <!-- картинка с графиком -->
                        <div class="row justify-content-center" v-bind:class="{ invisible: !welcomeImgLoaded, fadeInAnim: welcomeImgLoaded }">
                            <div class="col-8">
                                <img :src="welcomeImageUrl" width="90%" class="borderRadius40" @load="onWelcomeImgLoad">
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
        beforeMount(){
            //получить фоновое изображение
            if(this.homePageImageUrl == -1)
            { this.$store.dispatch('getHomePageImageUrl'); }

            //получить логотип сайта
            if(this.siteLogoUrl == -1)
            { this.$store.dispatch('getSiteLogoUrl'); }

            //получить изображение для приветствия
            if(this.welcomeImageUrl == -1)
            { this.$store.dispatch('getWelcomeImageUrl'); } 
        },

        mounted(){
            //получить юзернейм пользователя
            if(this.spotifyUsername == -1)
            { this.$store.dispatch('getSpotifyUsername'); }
          
            //получить кол-во треков в библиотеке для сообщения на главной странице
            if(this.spotifyUserTracksCount == -1)
            { this.$store.dispatch('getHomePageUserTracksCount'); }    

        },
        
        data(){
            return {
                welcomeImgLoaded: false,
            }
        },
        methods: {
            onWelcomeImgLoad(){
                this.welcomeImgLoaded = true;
            }
        },

        computed: {
            //юзернейм пользователя
            spotifyUsername: function(){
                return this.$store.state.homePage.spotifyUsername;
            },
            //кол-во треков в библиотеке
            spotifyUserTracksCount: function(){
                return this.$store.state.homePage.spotifyUserTracksCount;
            },
            //ссылка на логотип сайта
            siteLogoUrl: function(){
                return this.$store.state.homePage.siteLogoUrl;
            },
            //фоновое изображение
            homePageImageUrl: function(){
                return this.$store.state.homePage.homePageImageUrl;
            },
            //изображение для приветствия
            welcomeImageUrl: function(){
                return this.$store.state.homePage.welcomeImageUrl;
            }
        },
    }
</script>