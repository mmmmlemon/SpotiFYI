<template>
<div>
    <div class="row">
        <div class="col-md-12">
            <h5>
                <b>Общая информация</b>&nbsp;
                <i class="fas fa-chart-bar primary_color"></i>
            </h5>
        </div>
        <!-- tracks -->
        <LastFive :itemCount="spotifyTrackCount" :lastFiveItems="spotifyLastFiveTracks" type="tracks"/>  
        <!-- albums -->
        <LastFive :itemCount="spotifyAlbumCount" :lastFiveItems="spotifyLastFiveAlbums" type="albums"/>  
        <!-- artists -->
        <LastFive :itemCount="spotifyArtistsCount" :lastFiveItems="spotifyFiveArtists" type="artists"/>  
    </div>
    <hr class="fade_in_anim" v-if="spotifyFiveArtists !== -1 || spotifyLastFiveAlbums != -1 || spotifyLastFiveTracks != -1">
</div>
</template>

<script>
export default {
    beforeCreate(){

        //получить подписки
        this.$store.dispatch('getSpotifyArtistsCount');
        this.$store.dispatch('getSpotifyFiveArtists');
        //получить последние 5 альбомов и треков
        this.$store.dispatch('getSpotifyLastFive', "tracks");
        this.$store.dispatch('getSpotifyLastFive', "albums");
        //получить кол-во треков и альбомов в библиотеке
        this.$store.dispatch('getSpotifyTrackCount');
        this.$store.dispatch('getSpotifyAlbumCount');

    },
    computed: {
        //кол-во треков в библиотеке
        spotifyTrackCount: function() {
            return this.$store.state.profilePage.spotifyTrackCount;
        },
        //последние 5 треков
        spotifyLastFiveTracks: function(){
            return this.$store.state.profilePage.spotifyLastFiveTracks;
        },
        //кол-во альбомов в библиотеке
        spotifyAlbumCount: function(){
            return this.$store.state.profilePage.spotifyAlbumCount;
        },
        //кол-во подписок
        spotifyArtistsCount: function(){
            return this.$store.state.profilePage.spotifyArtistsCount;
        },
        spotifyFiveArtists: function(){
            return this.$store.state.profilePage.spotifyFiveArtists;
        },
        //последние 5 альбомов
        spotifyLastFiveAlbums: function(){
            return this.$store.state.profilePage.spotifyLastFiveAlbums;
        },
    }
}
</script>