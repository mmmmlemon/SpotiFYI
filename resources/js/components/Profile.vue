//Profile
<template>
    <div class="col-12">
        <!-- предупреждение о кукисах -->
        <Cookies />
        <!-- лоадер -->
        <div class="container bounceInAnim marginTopBig" v-if="spotifyProfile == -1">
            <Loader/>
        </div>
        <!-- ошибка -->
        <div v-if="spotifyProfile == false">
           <Error errorMessage="Не удалось загрузить профиль пользователя"/>
        </div>
        <!-- профиль -->
        <div class="container" id="top" v-if="spotifyProfile != -1 && spotifyProfile != false">
            <div class="col-12 greyCard">   
                <!-- юзернейм и ссылка на профиль -->
                <div class="row justify-content-center fadeInAnim">
                    <div class="col-12 col-md-8">
                        <h1 class="text-center fadeInAnimSlow paddingSides">
                            <a :href="spotifyProfile.profile_url" target="_blank"> 
                                <!-- юзернейм для десктопа -->
                                <b class="font3vw d-none d-md-block borderUnderline">{{spotifyProfile.spotifyUsername}}</b>
                                <!-- юзернейм для мобилок -->
                                <b class="font6vw d-sm-block d-md-none borderUnderline">{{spotifyProfile.spotifyUsername}}</b>
                            </a>
                        </h1>
                    </div>
                </div>
                <!-- аватарка -->
                <div class="row justify-content-center">
                    <img :src="spotifyProfile.avatar" alt="Spotify Avatar" class="profileAvatar" @load="onAvatarLoad"
                    v-bind:class="{ invisible: !avatarLoaded, bounceInAvatarAnim: avatarLoaded }">
                </div>
                <!-- вид подписки -->
                <div class="row justify-content-center fadeInAnimSlow">
                <h6 v-if="spotifyProfile.subscription == 'premium'" class="marginBottomNone">Premium <i class="fas fa-crown"></i></h6>
                </div>
                <!-- кол-во подписчиков и страна -->
                <div class="row justify-content-center fadeInAnimSlow marginVertical ">
                    <div> Подписчики: {{spotifyProfile.followers}}</div>
                    <div class="primaryColor">&nbsp;|&nbsp;</div>
                    <div>
                        <img :src="spotifyProfile.country">
                    </div>
                </div>
                <!-- разделитель -->
                <hr class="fadeInAnimSlow">
                <!-- кнопки меню -->
                <div class="row justify-content-center fadeInAnim">
                    <div class="col-12 col-md-9">
                        <div class="row justify-content-center">
                            <div class="col-md-4 paddingSides">
                                <router-link to="/profile">
                                    <button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'basicStats'}" type="button">
                                        Общее
                                    </button>
                                </router-link>
                            </div>
                            <div class="col-md-4 paddingSides">
                                <router-link to="/profile/top10">
                                    <button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'top10'}" type="button">
                                        Топ-10
                                    </button>
                                </router-link>
                            </div>
                            <div class="col-md-4 paddingSides">
                                <router-link to="/profile/achievements">
                                    <button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'achievements'}" type="button">
                                        Отличники
                                    </button>
                                </router-link>
                            </div>
                           </div>
                    </div>
                </div>
            </div>
         
        </div>
        <!-- контент -->
        <div class="container" v-if="spotifyProfile != -1 && spotifyProfile != false">
            <div class="row justify-content-center fadeInSlow">
                <div class="col-12 col-md-8" v-if="spotifyProfile != -1">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    beforeMount(){
        //получить фоновое изображение профиля
        if(this.profileImageUrl == -1)
        { this.$store.dispatch('getHomePageImageUrl'); }
    },
    mounted(){
        //получить профиль
        if(this.spotifyProfile == -1)
        { this.$store.dispatch('getSpotifyProfile'); }
    },
    data(){
        return {
            avatarLoaded: false,
        }
    },
    methods: {
        onAvatarLoad(){
            this.avatarLoaded = true;
        },
    },
    computed: {
        //фоновое изображение для профиля
        profileImageUrl: function(){
            return this.$store.state.homePage.homePageImageUrl;
        },

        //текущая вкладка
        currentTab: function(){
            return this.$store.state.profilePage.currentTab;
        },
        //фон профиля
        profileBackgroundUrl: function() {
            return this.$store.state.profilePage.profileBackgroundUrl;
        },
        //профиль
        spotifyProfile: function() {
            return this.$store.state.profilePage.spotifyProfile;
        }
    }
}
</script>