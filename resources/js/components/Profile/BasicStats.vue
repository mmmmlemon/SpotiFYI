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
                <div class="fade_in_anim" v-else-if="spotifyTrackCount >= 50">
                    <p>Треков в библиотеке - <b>{{spotifyTrackCount}}</b> </p>
                    <p v-if="spotifyTrackCount > 5000">{{spotifyTrackCount}}? Такое вообще возможно? Круть! 👍</p>
                    <p v-else-if="spotifyTrackCount > 1000">Ого как много! Да ты меломан! 😏</p>
                    <p v-else-if="spotifyTrackCount >= 500">Впечатляет! 😉</p>
                    <p v-else-if="spotifyTrackCount >= 100">Неплохо! 😌</p>
                    <p v-else-if="spotifyTrackCount >= 50">Нормально! Но лучше больше. 🤨</p>
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
                     <p>subtitle</p>
                </div>
            </div>
   
        </div>
    </div>
</template>

<script>
export default {
    beforeCreate(){
        //получить кол-во треков и альбомов в библиотеке
        this.$store.dispatch('getSpotifyTrackCount');
        this.$store.dispatch('getSpotifyAlbumCount');

    },
    computed: {
        //кол-во треков в библиотеке
        spotifyTrackCount: function() {
            return this.$store.state.profilePage.spotifyTrackCount;
        },
        spotifyAlbumCount: function(){
            return this.$store.state.profilePage.spotifyAlbumCount;
        }
    }
}
</script>