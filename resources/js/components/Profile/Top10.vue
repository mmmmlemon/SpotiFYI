<template>
   <div>
        <div>
            <div class="row justify-content-center">
                <div class="col-md-12 fade_in_slow_anim">
                    <h5 class="text-center">
                        <b>Топ 10</b>&nbsp;
                        <i class="fas fa-list-ol primary_color"></i>
                    </h5>
                </div>
                <div v-if="top10TracksAllTime == -1 || top10TracksMonth == -1">
                    <Loader />
                </div>
                <div v-else-if="top10TracksAllTime != -1 && top10TracksMonth != -1" class="row justify-content-center">
                    <!-- топ-10 треков -->
                    <Top10Items cardTitle="Топ 10 Треков за все время" 
                                cardDesc="Десять твоих самых прослушиваемых треков за все время." 
                                :items="top10TracksAllTime"/>
                    <Top10Items cardTitle="Топ 10 Треков за месяц" 
                                cardDesc="Десять твоих самых прослушиваемых треков за последний месяц." 
                                :items="top10TracksMonth"/>
                </div>
             
            </div>
        </div>
   </div>
</template>

<script>
export default {
    mounted(){
        this.$store.dispatch('setCurrentTab', 'top10');
        this.$store.dispatch('getTop10TracksAllTime');
        this.$store.dispatch('getTop10TracksMonth');
    },

    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
        },
        top10TracksAllTime: function() {
            return this.$store.state.profilePage.top10TracksAllTime;
        },
        top10TracksMonth: function() {
            return this.$store.state.profilePage.top10TracksMonth;
        }
    }
}
</script>