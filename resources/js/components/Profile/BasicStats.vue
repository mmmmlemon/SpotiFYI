<template>
<div>
    <!-- если библиотека пользователя не загружена, то показываем лоадер -->
    <div v-if="spotifyUserLibrary === -1">
        <Loader />
        <h6 class="text-center">Загружаю библиотеку пользователя...</h6>
    </div>
    <div v-else-if="spotifyUserLibrary === false">
        <Error errorMessage="Не удалось загрузить библиотеку пользователя." />
    </div>
       <!-- если библиотека загрузилась, то  -->
    <div v-else-if="spotifyUserLibrary !== false && spotifyUserLibrary !== -1">
        <div class="row justify-content-center">
            <div class="col-md-12 fade_in_anim">
                <h5 class="text-center">
                    <b>Общая информация</b>&nbsp;
                    <i class="fas fa-chart-bar primary_color"></i>
                </h5>
            </div>
            <!-- треки -->
            <LastFive :itemCount="spotifyTracks['count']" :lastFiveItems="spotifyTracks['last_five']" type="tracks"/>  
            <!-- альбомы -->
            <LastFive :itemCount="spotifyAlbums['count']" :lastFiveItems="spotifyAlbums['last_five']" type="albums"/>  
            <!-- исполнители -->
            <LastFive :itemCount="spotifyArtists['count']" :lastFiveItems="spotifyArtists['random_five']" type="artists"/>  
        </div>
    <hr class="fade_in_anim" v-if="spotifyArtists !== -1 || spotifyAlbums != -1 || spotifyTracks != -1">

    <!-- часы и время -->
    <HoursAndMinutes v-if="spotifyTracks !== -1 && spotifyAlbums != -1 && spotifyArtists != -1" class="fade_in_slow_anim"
        :userLibraryTime="userLibraryTime" :fiveLongest="fiveLongest"/>
    <hr v-if="spotifyTracks !== -1 && spotifyAlbums != -1 && spotifyArtists != -1">
    </div>

</div>
</template>

<script>
export default {
 
    beforeCreate(){
        //получаем библиотеку пользователя и статистику
        this.$store.dispatch('getSpotifyUserLibrary');
        this.$store.dispatch('getSpotifyTracks');
        this.$store.dispatch('getSpotifyAlbums');
        this.$store.dispatch('getSpotifyArtists');

        //время
        this.$store.dispatch('getUserLibraryTime');
        this.$store.dispatch('getFiveLongest');
    },
    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
        },
        //кол-во треков и последние пять
        spotifyTracks: function() {
            return this.$store.state.profilePage.spotifyTracks;
        },
        //кол-во альбомов и последние пять
        spotifyAlbums: function() {
            return this.$store.state.profilePage.spotifyAlbums;
        },
        //кол-во подписок и случайные пять
        spotifyArtists: function(){
            return this.$store.state.profilePage.spotifyArtists;
        },
        //время
        userLibraryTime: function(){
            return this.$store.state.profilePage.userLibraryTime;
        },
        //пять самых длинных
        fiveLongest: function(){
            return this.$store.state.profilePage.fiveLongest;
        }
    }
}
</script>