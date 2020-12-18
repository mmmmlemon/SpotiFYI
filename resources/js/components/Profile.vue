<template>
    <div>
        <div class="container bounce_in_anim" v-if="spotifyProfile == -1">
            <Loader/>
        </div>
        <div v-if="spotifyProfile == false">
           <Error errorMessage="Не удалось загрузить профиль пользователя"/>
        </div>
        <!-- профиль -->
        <div class="container" v-if="spotifyProfile != -1 && spotifyProfile != false">

            <div class="col-12 grey_card">
                <!-- юзернейм и ссылка на профиль -->
                <div class="row justify-content-center fade-in">
                        <h1 class="fade_in_anim">
                            <b>{{spotifyProfile.spotifyUsername}}</b>
                            <a :href="spotifyProfile.profile_url" target="_blank" style="font-size:10pt;"><i class="fas fa-external-link-alt"></i></a>
                        </h1>
                </div>
                <!-- аватарка -->
                <div class="row justify-content-center">
                        <img :src="spotifyProfile.avatar" alt="Spotify Avatar" class="profile_avatar bounce_in_av_anim">
                </div>
                <!-- вид подписки -->
                <div class="row justify-content-center fade-in">
                <h6 v-if="spotifyProfile.subscription == 'premium'" style="margin-bottom:0;">Premium <i class="fas fa-crown"></i></h6>
                <h6 v-else></h6>
                </div>
                <!-- кол-во подписчиков и страна -->
                <div class="row justify-content-center">
                    <div> Подписчики: 1</div>
                    <div>&nbsp;|&nbsp;</div>
                    <div>
                        <img class="fade_in_anim" :src="spotifyProfile.country">
                    </div>
                </div>
                <!-- разделитель -->
                <hr class="fade_in_anim">
                <!-- кнопки меню -->
                <div class="row justify-content-center fade-in">
                    <div class="col-md-8">
                        <div class="row fade_in_anim">
                            <div class="col-md-3 padding_10">
                                <router-link to="/profile"><button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'basicStats'}" type="button">Общее</button></router-link>
                            </div>
                            <div class="col-md-3 padding_10">
                                <router-link to="/profile/top10"><button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'top10'}" type="button">Топ-10</button></router-link>
                            </div>
                            <div class="col-md-3 padding_10">
                                <router-link to="/"><button class="btn btn-block" type="button">Что-то</button></router-link>
                            </div>
                            <div class="col-md-3 padding_10">
                                <router-link to="/"><button class="btn btn-block" type="button">Еще</button></router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         
        </div>
        <!-- контент -->
        <div class="container">
            <div class="row justify-content-center fade-in">
                <div class="col-md-8" v-if="spotifyProfile != -1">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    beforeCreate(){
        //получить профиль
        this.$store.dispatch('getSpotifyProfile');
        //получаем библиотеку пользователя и статистику
        this.$store.dispatch('getSpotifyUserLibrary');
        // //треки, альбомы и подписки
        // this.$store.dispatch('getSpotifyTracks');
        // this.$store.dispatch('getSpotifyAlbums');
        // this.$store.dispatch('getSpotifyArtists');
        // //время
        // this.$store.dispatch('getUserLibraryTime');
        // this.$store.dispatch('getFiveLongestAndShortestTracks');
        // this.$store.dispatch('getAverageLengthOfTrack');
        // //жанры
        // this.$store.dispatch('getFavoriteGenres');
        // //кол-во исполнителей
        // this.$store.dispatch('getUniqueArtists');
        // //года и десятилетия
        // this.$store.dispatch('getYearsAndDecades');
    },
    computed: {
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