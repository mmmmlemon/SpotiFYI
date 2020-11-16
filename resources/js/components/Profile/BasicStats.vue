<template>
    <div class="row">
         <div class="col-md-12">
            <h5><b>Общая информация</b>&nbsp;<i class="fas fa-chart-bar primary_color"></i></h5>
         </div>
        <!-- tracks -->
        <div class="col-md-4">
            <div v-if="spotifyTrackCount == false">
                <Error type="small" errorMessage = "Не удалось загрузить данные по трекам"/>
            </div>
            <div v-if="spotifyTrackCount != false">
                <div v-if="spotifyTrackCount == -1">
                    <Loader />
                </div>
                <div v-else-if="spotifyTrackCount >= 50" class="fade_in_anim">
                    <p>Треков в библиотеке - <b>{{spotifyTrackCount}}</b></p>
                    <div class="col-md-12">
                        <p style="font-size: 10pt;">Последние добавленные треки</p>
                    </div>
                    <div v-if="spotifyLastFiveTracks == false">
                        <Error type="x-small" errorMessage="Не удалось загрузить треки"/>
                    </div>
                    <div v-else-if="spotifyLastFiveTracks == -1">
                        <Loader />
                    </div>
                    <div v-else-if="spotifyLastFiveTracks.length > 0" class="col-md-12 fade_in_anim">  
                         <div class="row">
                            <div data-toggle="tooltip" :data-title="track.name" data-placement="bottom" class="col-md-2" 
                                v-for="track in spotifyLastFiveTracks" :key="track.id">
                                <a :href="track.url" target="_blank">
                                    <img class="rounded-circle album_icon" :src="track.cover" style="width:80%; margin:5px;">
                                </a>
                            </div>
                         </div>
                    </div>  
                </div>
                <div v-else-if="spotifyTrackCount < 50">
                    <p>Треков в библиотеке - <b>{{spotifyTrackCount}}</b> </p>
                    <p>Мало треков. Что мне анализировать? Приходи назад когда добавишь чего-нибудь!</p>
                </div>
                <div v-else>
                    <Error type="small" errorMessage = "Неизвестная ошибка"/>
                </div>  
            </div>
        </div>
        <!-- albums -->
        <div class="col-md-4">
            <div v-if="spotifyAlbumCount == false">
                <Error type="small" errorMessage="Не удалось загрузить данные по альбомам"/>
            </div>
            <div v-if="spotifyAlbumCount != false">
                <div v-if="spotifyAlbumCount == -1">
                    <Loader />
                </div>
                <div class="fade_in_anim" v-else-if="spotifyAlbumCount > 0">
                    <p>Альбомов в библиотеке - <b>{{spotifyAlbumCount}}</b></p>
                    <!-- last 5 albums -->
                    <div v-if="spotifyLastFiveAlbums == false">
                        <Error type="x-small" errorMessage="Не удалось загрузить альбомы"/>
                    </div>
                    <div v-else-if="spotifyLastFiveAlbums == -1">
                        <Loader />
                    </div>
                    <div v-else-if="spotifyLastFiveAlbums != -1" class="col-md-12 fade_in_anim">
                        <p style="font-size: 10pt;">Последние добавленные альбомы</p>
                        <div class="col-md-12">  
                            <div class="row">
                                <div data-toggle="tooltip" :data-title="album.name" data-placement="bottom" class="col-md-2" 
                                    v-for="album in spotifyLastFiveAlbums" :key="album.id">
                                    <a :href="album.url" target="_blank">
                                        <img class="rounded-circle album_icon" :src="album.cover" style="width:80%; margin:5px;">
                                    </a>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div v-else-if="spotifyLastFiveAlbums === 0">
                    <p>Альбомов в библиотеке - <b>{{spotifyAlbumCount}}</b> </p>
                        <p>Мало альбомов. Добавь чего-нибудь!</p>
                    </div>
                    <div v-else>
                        <Error type="small" errorMessage = "Неизвестная ошибка"/>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    beforeCreate(){
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
        //последние 5 альбомов
        spotifyLastFiveAlbums: function(){
            return this.$store.state.profilePage.spotifyLastFiveAlbums;
        },
    }
}
</script>