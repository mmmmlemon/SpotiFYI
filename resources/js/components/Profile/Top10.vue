<template>
   <div>
        <div>
            <div class="row justify-content-center">
                <div class="col-12" v-if="spotifyUserLibrary == -1">
                    <Loader />
                    <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
                    <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == true">Анализирую треки...</h6>
                    <p class="font_10pt text-center">Это может занять около минуты</p>
                </div>
                <div v-else-if="spotifyUserLibrary != -1" class="row justify-content-center">
                    <div class="col-md-12 fade_in_slow_anim">
                        <h5 class="text-center">
                            <b>Топ 10</b>&nbsp;
                            <i class="fas fa-list-ol primary_color"></i>
                        </h5>
                    </div>
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
                    <div class="col-12 justify-content-center fade_in_anim" id="tracks">
                        <h3 class="text-center">
                            Треки
                            <i class="fas fa-compact-disc primary_color"></i>
                        </h3>
                    </div>
               
                    <Top10Items cardTitle="Топ 10 Треков за все время" 
                                cardDesc="Десять твоих самых прослушиваемых треков за все время." 
                                :items="top10TracksAllTime"
                                listType="tracks"/>

                    <Top10Items v-if="top10TracksAllTime != -1"
                                loaderMessage="Загружаю Топ 10 треков за месяц..."
                                cardTitle="Топ 10 Треков за месяц" 
                                cardDesc="Десять твоих самых прослушиваемых треков за последний месяц." 
                                :items="top10TracksMonth"
                                listType="tracks"/>

                    <Top10Items v-if="top10TracksMonth != -1"
                                cardTitle="Топ 10 самых длинных" 
                                cardDesc="Десять твоих самых длинных треков в библиотеке." 
                                :items="top10TracksLong"
                                listType="tracks"/> 

                    <Top10Items v-if="top10TracksLong != -1"
                                cardTitle="Топ 10 самых коротких" 
                                cardDesc="Десять твоих самых коротких треков в библиотеке." 
                                :items="top10TracksShort"
                                listType="tracks"/>

                    <!-- топ-10 треков -->
                    <div class="col-12 justify-content-center" id="artists" v-if="top10TracksShort != -1">
                        <h3 class="text-center">
                            Исполнители
                            <i class="fas fa-users primary_color"></i>
                        </h3>
                    </div>

                    <Top10Items v-if="top10TracksShort != -1"
                                cardTitle="Топ 10 артистов за все время" 
                                cardDesc="Десять твоих самых прослушиваемых артистов за все время." 
                                :items="top10ArtistsAllTime"
                                listType="artists"/>

                    <Top10Items v-if="top10ArtistsAllTime != -1"
                                cardTitle="Топ 10 артистов за месяц" 
                                cardDesc="Десять твоих самых прослушиваемых артистов за последний месяц." 
                                :items="top10ArtistsMonth"
                                listType="artists"/>

                    <Top10Items v-if="top10ArtistsMonth != -1"
                                cardTitle="Топ 10 артистов по трекам" 
                                cardDesc="Десять артистов с наибольшим кол-вом треков в твоей библиотеке." 
                                :items="top10ArtistsByTracks"
                                listType="artists"/>
                    <Top10Items v-if="top10ArtistsByTracks != -1"
                                cardTitle="Топ 10 артистов по времени треков" 
                                cardDesc="Десять артистов с наибольшим кол-вом часов музыки в твоей библиотеке." 
                                :items="top10ArtistsByTime"
                                listType="artists"/>
                </div>
             
            </div>
        </div>
        <br>
        <div class="row justify-content-center fade_in_anim" v-if="top10ArtistsByTime != -1">
            <router-link to="/profile/achievements"><button class="btn btn-primary">Перейти к "Особо отличившиеся"</button></router-link>
            <br><br>
        </div>
   </div>
</template>

<script>
export default {
    mounted(){

        //смена текущего таба
        this.$store.dispatch('setCurrentTab','top10');

        //получить библиотеку пользователя, если нужно
        if(this.spotifyUserLibrary == -1)
        { this.$store.dispatch('getSpotifyUserLibrary'); }
    
        //топ 10 треков за всё время
        if(this.top10TracksAllTime == -1)
        { this.$store.dispatch('getTop10TracksAllTime'); }

        //топ 10 треков за месяц
        if(this.top10TracksMonth == -1)
        { this.$store.dispatch('getTop10TracksMonth'); }

        //топ 10 длинных треков
        if(this.top10TracksLong == -1)
        { this.$store.dispatch('getTop10TracksLong'); }

        //топ 10 коротких треков
        if(this.top10TracksShort == -1)
        { this.$store.dispatch('getTop10TracksShort'); }

        //топ 10 артистов за все время
        if(this.top10ArtistsAllTime == -1)
        { this.$store.dispatch('getTop10ArtistsAllTime'); }

        //топ 10 артистов за месяц
        if(this.top10ArtistsMonth == -1)
        { this.$store.dispatch('getTop10ArtistsMonth'); }

        //топ 10 артистов по кол-ву треков
        if(this.top10ArtistsByTracks == -1)
        { this.$store.dispatch('getTop10ArtistsByTracks'); }

        //топ 10 артистов по кол-ву времени
        if(this.top10ArtistsByTime == -1)
        { this.$store.dispatch('getTop10ArtistsByTime'); }   
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