<template>
<div>
    <!-- если библиотека пользователя не загружена, то показываем лоадер -->
    <div v-if="spotifyUserLibrary === -1 || favoriteGenres === -1" >
        <Loader />
        <h6 v-if="spotifyUserLibrary === -1 && favoriteGenres === -1" class="text-center blinking_anim">Загружаю библиотеку пользователя...</h6>
        <h6 v-if="spotifyUserLibrary != -1 && favoriteGenres === -1" class="text-center blinking_anim">Анализирую треки...</h6>
        <p class="font_10pt text-center">Это может занять около минуты</p>
    </div>
    <div v-else-if="spotifyUserLibrary === false || favoriteGenres === false">
        <Error errorMessage="Не удалось загрузить библиотеку пользователя." />
    </div>
       <!-- если библиотека загрузилась, то  -->
    <div v-else-if="spotifyUserLibrary !== false && spotifyUserLibrary !== -1">
        <div class="row justify-content-center">
            <div class="col-md-12 fade_in_slow_anim">
                <h5 class="text-center">
                    <b>Общая информация</b>&nbsp;
                    <i class="fas fa-chart-bar primary_color"></i>
                </h5>
            </div>
            <!-- треки -->
            <LastFive :itemCount="spotifyTracks['count']" :lastFiveItems="spotifyTracks['last_five']" type="tracks"/>  
            <!-- альбомы -->
            <LastFive v-if="spotifyTracks !== false && spotifyTracks !== -1" 
                        :itemCount="spotifyAlbums['count']" :lastFiveItems="spotifyAlbums['last_five']" type="albums"/>  
            <!-- исполнители -->
            <LastFive v-if="spotifyAlbums !== false && spotifyAlbums !== -1" 
                        :itemCount="spotifyArtists['count']" :lastFiveItems="spotifyArtists['random_five']" type="artists"/>  
        </div>
        <hr class="fade_in_anim" v-if="spotifyArtists !== -1 || spotifyAlbums != -1 || spotifyTracks != -1">
        <!-- часы и время -->
        <HoursAndMinutes v-if="spotifyArtists !== -1 && spotifyAlbums != -1 && spotifyTracks != -1" 
                        class="fade_in_slow_anim" :userLibraryTime="userLibraryTime"/>
        <!-- самые длинные и короткие треки -->
        <LongestAndShortest v-if="userLibraryTime !== -1"
                            :fiveLongest="fiveTracks['fiveLongest']" :fiveShortest="fiveTracks['fiveShortest']" :tracksMode="tracksMode"/>
        <!-- любимые жанры -->
        <FavoriteGenres v-if="tracksMode != -1" :favoriteGenres="favoriteGenres"/>
        <!-- кол-во исполнителей -->
        <ArtistsCount v-if="favoriteGenres != -1" :uniqueArtists="uniqueArtists"/>
        <!-- года и десятилетия -->
        <YearsAndDecades v-if="uniqueArtists != -1" :yearsAndDecades="yearsAndDecades"/>
    </div>

</div>
</template>

<script>
export default {
 
    beforeCreate(){
        //получаем библиотеку пользователя и статистику
        this.$store.dispatch('getSpotifyUserLibrary');
        //треки, альбомы и подписки
        this.$store.dispatch('getSpotifyTracks');
        this.$store.dispatch('getSpotifyAlbums');
        this.$store.dispatch('getSpotifyArtists');
        //время
        this.$store.dispatch('getUserLibraryTime');
        this.$store.dispatch('getFiveLongestAndShortestTracks');
        this.$store.dispatch('getAverageLengthOfTrack');
        //жанры
        this.$store.dispatch('getFavoriteGenres');
        //кол-во исполнителей
        this.$store.dispatch('getUniqueArtists');
        //года и десятилетия
        this.$store.dispatch('getYearsAndDecades');
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
        fiveTracks: function(){
            return this.$store.state.profilePage.fiveTracks;
        },
        //средняя длина трека
        tracksMode: function() {
            return this.$store.state.profilePage.tracksMode;
        },
        //любимые жанры
        favoriteGenres: function(){
            return this.$store.state.profilePage.favoriteGenres;
        },
        //кол-во исполнителей
        uniqueArtists: function(){
            return this.$store.state.profilePage.uniqueArtists;
        },
        //года и десятилетия
        yearsAndDecades: function(){
            return this.$store.state.profilePage.yearsAndDecades;
        },
    }
}
</script>