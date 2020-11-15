<template>
    <div>
        <div v-if="spotifyTrackCount === false">
            <Error errorMessage = "Не удалось загрузить данные пользователя"/>
        </div>
        <div  v-if="spotifyTrackCount !== false">
            <h5><b>Общая информация</b>&nbsp;<i class="fas fa-chart-bar primary_color"></i></h5>
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
                <Error errorMessage = "Неизвестная ошибка"/>
            </div>
           
        </div>
    </div>
</template>

<script>
export default {
    beforeCreate(){
        //получить кол-во треков в библиотеке
        this.$store.dispatch('getSpotifyTrackCount');
    },
    computed: {
        //кол-во треков в библиотеке
        spotifyTrackCount: function() {
            return this.$store.getters.getSpotifyTrackCount;
        }
    }
}
</script>