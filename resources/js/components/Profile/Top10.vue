<template>
   <div>
        <!-- если библиотека пользователя не загружена, то показываем лоадер -->
        <div v-if="spotifyUserLibrary === -1">
            <Loader />
            <h6 v-if="spotifyUserLibrary === -1" class="text-center blinking_anim">Загружаю библиотеку пользователя...</h6>
            <h6 v-if="spotifyUserLibrary != -1" class="text-center blinking_anim">Анализирую треки...</h6>
            <p class="font_10pt text-center">Это может занять около минуты</p>
        </div>
        <div v-else-if="spotifyUserLibrary === false">
            <Error errorMessage="Не удалось загрузить библиотеку пользователя." />
        </div>
        <!-- если библиотека загрузилась, то  -->
        <div v-else-if="spotifyUserLibrary !== false && spotifyUserLibrary !== -1">
            <div class="row justify-content-center">
                <div class="col-md-12 fade_in_slow_anim">
                    <h5 class="text-center">
                        <b>Топ 10</b>&nbsp;
                        <i class="fas fa-list-ol primary_color"></i>
                    </h5>
                </div>

                <div class="row justify-content-center">
                    <!-- топ-10 треков -->
                    <Top10Items cardTitle="Топ 10 Треков за все время" cardDesc="Десять твоих самых прослушиваемых треков за все время." :items="top10Tracks"/>
                    <!-- <Top10Items cardTitle="Топ 10 Треков за месяц" cardDesc="Десять твоих самых прослушиваемых треков за последний месяц." :items="top10Tracks"/> -->
                </div>
             
            </div>
        </div>
   </div>
</template>

<script>
export default {
    mounted(){
        this.$store.dispatch('setCurrentTab', 'top10');
        this.$store.dispatch('getTop10Tracks');
    },

    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
        },
        top10Tracks: function() {
            return this.$store.state.profilePage.top10Tracks;
        }
    }
}
</script>