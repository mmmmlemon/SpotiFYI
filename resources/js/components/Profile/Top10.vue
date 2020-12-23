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
        
                <div class="col-12" v-if="top10TracksAllTime == -1 || top10TracksMonth == -1">
                    <Loader />
                    <h6 class="text-center blinking_anim">Загружаю библиотеку...</h6>
                    <p class="font_10pt text-center">Это может занять около минуты</p>
                </div>
                <div v-else-if="top10TracksAllTime != -1 && top10TracksMonth != -1" class="row justify-content-center">
                    <!-- навигация -->
                    <div class="row justify-content-center font_10pt fade_in_anim">
                        <nav class="justify-content-center">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#tracks">Треки</a></li>
                                <li class="breadcrumb-item"><a href="#artists">Исполнители</a></li>
                            </ul>
                        </nav>
                    </div>

                    <!-- топ-10 треков -->
                    <div class="col-12 justify-content-center" id="tracks">
                        <h3 class="text-center">
                            Треки
                            <i class="fas fa-compact-disc primary_color"></i>
                        </h3>
                    </div>
               
                    <Top10Items cardTitle="Топ 10 Треков за все время" 
                                cardDesc="Десять твоих самых прослушиваемых треков за все время." 
                                :items="top10TracksAllTime"
                                listType="tracks"/>

                    <Top10Items cardTitle="Топ 10 Треков за месяц" 
                                cardDesc="Десять твоих самых прослушиваемых треков за последний месяц." 
                                :items="top10TracksMonth"
                                listType="tracks"/>

                    <Top10Items cardTitle="Топ 10 самых длинных" 
                                cardDesc="Десять твоих самых длинных треков в библиотеке." 
                                :items="top10TracksLong"
                                listType="tracks"/> 

                    <Top10Items cardTitle="Топ 10 самых коротких" 
                                cardDesc="Десять твоих самых коротких треков в библиотеке." 
                                :items="top10TracksShort"
                                listType="tracks"/>

                    <!-- топ-10 треков -->
                    <div class="col-12 justify-content-center" id="artists">
                        <h3 class="text-center">
                            Исполнители
                            <i class="fas fa-users primary_color"></i>
                        </h3>
                    </div>

                    <Top10Items cardTitle="Топ 10 артистов за все время" 
                                cardDesc="Десять твоих самых прослушиваемых артистов за все время." 
                                :items="top10ArtistsAllTime"
                                listType="artists"/>

                    <Top10Items cardTitle="Топ 10 артистов за месяц" 
                                cardDesc="Десять твоих самых прослушиваемых артистов за последний месяц." 
                                :items="top10ArtistsMonth"
                                listType="artists"/>

                    <Top10Items cardTitle="Топ 10 артистов по трекам" 
                                cardDesc="Десять артистов с наибольшим кол-вом треков в твоей библиотеке." 
                                :items="top10ArtistsByTracks"
                                listType="artists"/>
                    <Top10Items cardTitle="Топ 10 артистов по времени треков" 
                                cardDesc="Десять артистов с наибольшим кол-вом часов музыки в твоей библиотеке." 
                                :items="top10ArtistsByTime"
                                listType="artists"/>
                </div>
             
            </div>
        </div>
   </div>
</template>

<script>
export default {
    mounted(){
        this.$store.dispatch('setCurrentTab','top10');
        this.$store.dispatch('getTop10TracksAllTime');
        this.$store.dispatch('getTop10TracksMonth');
        this.$store.dispatch('getTop10ArtistsAllTime');
        this.$store.dispatch('getTop10ArtistsMonth');
        this.$store.dispatch('getTop10TracksLong');
        this.$store.dispatch('getTop10TracksShort');
        this.$store.dispatch('getTop10ArtistsByTracks');
        this.$store.dispatch('getTop10ArtistsByTime');
    },

    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            // return this.$store.state.profilePage.spotifyUserLibrary;
            return true;
        },
        top10TracksAllTime: function() {
            return this.$store.state.profilePage.top10TracksAllTime;
        },
        top10TracksMonth: function() {
            return this.$store.state.profilePage.top10TracksMonth;
        },
        top10ArtistsAllTime: function(){
            return this.$store.state.profilePage.top10ArtistsAllTime;
        },
        top10ArtistsMonth: function(){
            return this.$store.state.profilePage.top10ArtistsMonth;
        },
        top10TracksLong: function(){
            return this.$store.state.profilePage.top10TracksLong;
        },
        top10TracksShort: function(){
            return this.$store.state.profilePage.top10TracksShort;
        },
        top10ArtistsByTracks: function() {
            return this.$store.state.profilePage.top10ArtistsByTracks;
        },
        top10ArtistsByTime: function() {
            return this.$store.state.profilePage.top10ArtistsByTime;
        }
    }
}
</script>