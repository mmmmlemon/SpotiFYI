<template>
    <div class="row justify-content-center">
        <div class="col-12" v-if="spotifyUserLibrary == -1">
            <Loader />
            <h6 class="text-center blinkingAnim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
            <h6 class="text-center blinkingAnim" v-if="spotifyUserLibrary == true">Анализирую треки...</h6>
            <p class="text-center font10pt">Это может занять около минуты</p>
        </div>
        <div v-else-if="spotifyUserLibrary != -1 && spotifyUserLibrary['result'] != false" class="col-12">
            <div class="col-md-12 fadeInAnimSlow">
                <h5 class="text-center">
                    <b>Особо отличившиеся</b>&nbsp;
                    <i class="fas fa-award primaryColor"></i>
                </h5>
            </div>
            <!-- навигация -->
            <div class="row justify-content-center font10pt fadeInAnim">
                <nav class="justify-content-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#tracks">Треки</a></li>
                        <li class="breadcrumb-item"><a href="#artists">Исполнители</a></li>
                    </ul>
                </nav>
            </div>
            <!-- треки -->
            <div class="col-12 justify-content-center fadeInAnim" id="tracks">
                <h3 class="text-center">
                    Треки
                    <i class="fas fa-compact-disc primaryColor"></i>
                </h3>
            </div>

            <div class="row justify-content-center">
                <AchievementItem v-if="mostListenedTrack != 'noTracks'" 
                                cardTitle="Самый прослушиваемый трек" 
                                cardSubtitle="За всё время" 
                                :items="mostListenedTrack"/>

                <AchievementItem v-if="mostListenedTrack != -1 && mostListenedTrackMonth != 'noTracks'" 
                                cardTitle="Самый прослушиваемый трек" cardSubtitle="За месяц" 
                                :items="mostListenedTrackMonth"/>

                <AchievementItem v-if="mostListenedTrackMonth != -1" 
                                cardTitle="Самый популярный трек"  
                                cardSubtitle="Который тебе нравится" 
                                :items="mostPopularTrack"/>

                <AchievementItem v-if="mostPopularTrack != -1" 
                                cardTitle="Самый непопулярный трек"  
                                cardSubtitle="Который тебе нравится" 
                                :items="leastPopularTrack"/>


                <AchievementItem v-if="leastPopularTrack != -1" 
                                cardTitle="Самый длинный трек"  
                                cardSubtitle="Который тебе нравится" 
                                :items="longestTrack"/>

                <AchievementItem v-if="longestTrack != -1" 
                                cardTitle="Самый короткий трек"  
                                cardSubtitle="Который тебе нравится" 
                                :items="shortestTrack"/>
            </div>

            <!-- исполнители -->
            <div v-if="shortestTrack != -1" class="col-12 justify-content-center fadeInAnim" id="artists">
                <h3 class="text-center">
                    Исполнители
                    <i class="fas fa-users primaryColor"></i>
                </h3>
            </div>

            <div class="row justify-content-center" v-if="shortestTrack != -1">
                <AchievementItem v-if="shortestTrack != -1 && mostListenedArtist != 'noArtists'" 
                                cardTitle="Самый слушаемый исполнитель" cardSubtitle="За всё время" 
                                :items="mostListenedArtist"/>

                <AchievementItem v-if="mostListenedArtist != -1 && mostListenedArtistMonth != 'noArtists'" 
                                cardTitle="Самый слушаемый исполнитель" cardSubtitle="За месяц" 
                                :items="mostListenedArtistMonth"/>

                <AchievementItem v-if="mostListenedArtistMonth != -1" 
                                cardTitle="Исполнитель" 
                                cardSubtitle="С наибольшим кол-вом треков" 
                                :items="topArtistByTracks"/>

                <AchievementItem v-if="topArtistByTracks != -1" 
                                cardTitle="Исполнитель" 
                                cardSubtitle="С наибольшим кол-вом времени треков" 
                                :items="topArtistByTime"/>

                <AchievementItem v-if="topArtistByTime != -1 && mostPopularArtist != 'noArtists'" 
                                cardTitle="Самый популярный исполнитель" cardSubtitle="На которого ты подписан" 
                                :items="mostPopularArtist"/>

                <AchievementItem v-if="mostPopularArtist != -1 && leastPopularArtist != 'noArtists'" 
                                cardTitle="Самый непопулярный исполнитель" cardSubtitle="На которого ты подписан" 
                                :items="leastPopularArtist"/>
            </div>
                       <br>
            <div class="row justify-content-center fadeInAnim" v-if="leastPopularArtist != -1">
                
                <router-link to="/profile#top">
                    <button class="btn btn-primary">
                        Перейти к "Общее"
                        <i class="fas fas fa-chart-bar"></i>
                    </button>
                </router-link>
                <br><br>
            </div>
        </div>
        <div v-else-if="spotifyUserLibrary == false">
            <Error errorMessage="Не удалось загрузить библиотеку пользователя"/>
        </div>
        <div v-else>
            <Info :infoMessage="spotifyUserLibrary['errorMsg']"/>
        </div>
    </div>
</template>

<script>
export default {
    mounted(){
        var anchor=this.$router.currentRoute.hash.replace("#", "");

        if(anchor)
        { this.$nextTick(()=> window.document.getElementById(anchor).scrollIntoView()); }

        //смена текущего таба
        this.$store.dispatch('setCurrentTab','achievements');

        //получить библиотеку пользователя, если нужно
        if(this.spotifyUserLibrary == -1)
        { 
            this.$store.dispatch('getSpotifyUserLibrary').then(response => {
                if(this.spotifyUserLibrary['result'] == true)
                {
                    this.getAllData();
                }
            }, error => {
                console.log("Error: Couldn't load user's Spotify library.");
            }); 
        }
        else
        { this.getAllData(); }
 
    },
    methods: {
        getAllData: function(){
            
            //самый прослушиваемый трек
            if(this.mostListenedTrack == -1)
            { this.$store.dispatch('getMostListenedTrack', 'alltime') };

            //самый прослушиваемый трек за месяц
            if(this.mostListenedTrack == -1)
            { this.$store.dispatch('getMostListenedTrack', 'month') };

            //самый популярный трек
            if(this.mostPopularTrack == -1)
            { this.$store.dispatch('getTrackByPopularity', 'popular'); }

            //самый непопулярный трек
            if(this.leastPopularTrack == -1)
            { this.$store.dispatch('getTrackByPopularity', 'unpopular'); }
            
            //самый длинный трек
            if(this.longestTrack == -1)
            { this.$store.dispatch('getTrackByDuration', 'long'); }

            //самый короткий трек
            if(this.shortestTrack == -1)
            { this.$store.dispatch('getTrackByDuration', 'short'); }

            //самый слушаемый артист
            if(this.mostListenedArtist == -1)
            { this.$store.dispatch('getMostListenedArtist', 'alltime'); }

            //самый слушаемый артист за месяц
            if(this.mostListenedArtistMonth == -1)
            { this.$store.dispatch('getMostListenedArtist', 'month'); }

            //артист с наибольшим кол-вом треков
            if(this.topArtistByTracks == -1)
            { this.$store.dispatch('getArtistByTracks'); }

            //артист с наибольшим кол-вом времени треков
            if(this.topArtistByTime == -1)
            { this.$store.dispatch('getArtistByTime'); }

            //cамый популярный артист, из подписок
            if(this.mostPopularArtist == -1)
            { this.$store.dispatch('getArtistByPopularity', 'popular'); }

            //cамый непопулярный артист, из подписок
            if(this.mostPopularArtist == -1)
            { this.$store.dispatch('getArtistByPopularity', 'unpopular'); }


        },
    },
    computed: {
        spotifyUserLibrary: function(){
            return this.$store.state.profilePage.spotifyUserLibrary;
            // return true;
        },
        mostListenedTrack: function(){
            return this.$store.state.profilePage.mostListenedTrack;
        },
        mostListenedTrackMonth: function(){
            return this.$store.state.profilePage.mostListenedTrackMonth;
        },
        mostPopularTrack: function(){
            return this.$store.state.profilePage.mostPopularTrack;
        },
        leastPopularTrack: function(){
            return this.$store.state.profilePage.leastPopularTrack;
        },
        longestTrack: function(){
            return this.$store.state.profilePage.longestTrack;
        },
        shortestTrack: function(){
            return this.$store.state.profilePage.shortestTrack;
        },
        mostListenedArtist: function(){
            return this.$store.state.profilePage.mostListenedArtist;
        },
        mostListenedArtistMonth: function(){
            return this.$store.state.profilePage.mostListenedArtistMonth;
        },
        topArtistByTracks: function(){
            return this.$store.state.profilePage.topArtistByTracks;
        },
        topArtistByTime: function(){
            return this.$store.state.profilePage.topArtistByTime;
        },
        mostPopularArtist: function(){
            return this.$store.state.profilePage.mostPopularArtist;
        },
        leastPopularArtist: function(){
            return this.$store.state.profilePage.leastPopularArtist;
        },
    }
}
</script>