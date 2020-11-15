<template>
    <div>
        <div v-if="spotifyTrackCount == false">
            <Error errorMessage = "Не удалось загрузить данные пользователя"/>
        </div>
        <div  v-if="spotifyTrackCount != false">
            <h5><b>Общая информация</b>&nbsp;<i class="fas fa-chart-bar primary_color"></i></h5>
             <div v-if="spotifyTrackCount == -1">
                <Loader />
            </div>
            <div class="fade_in_anim" v-else-if="spotifyTrackCount > 0">
                <p>В твою библиотеку добавлено <b>{{spotifyTrackCount}}</b> треков. </p>
                <p>Ого! Неплохо!</p>
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