<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-12" v-if="spotifyUserLibrary == -1">
                <Loader />
                <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
                <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == true">Анализирую треки...</h6>
                <p class="font_10pt text-center">Это может занять около минуты</p>
            </div>
            <div v-else-if="spotifyUserLibrary != -1 && spotifyUserLibrary['result'] != false 
                && spotifyUserLibrary['result'] != 'libraryError'" class="row justify-content-center">
                <div class="col-md-12 fade_in_slow_anim">
                    <h5 class="text-center">
                        <b>Общая статистика</b>&nbsp;
                        <i class="fas fa-chart-bar primary_color"></i>
                    </h5>
                </div>
                <!-- навигация -->
                <div class="row justify-content-center font_10pt fade_in_anim">
                    <nav class="justify-content-center">
                        <ul class="breadcrumb text-center">
                            <li class="breadcrumb-item"><a href="#basic">Общее</a></li>
                            <li class="breadcrumb-item"><a href="#tracks">Самые длинные и короткие треки</a></li>
                            <li class="breadcrumb-item"><a href="#genres">Жанры и годы</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="col-12 justify-content-center fade_in_anim">
                </div>
            
                <div class="row justify-content-center" id="basic">
                    <!-- треки -->
                    <LastFive :items="spotifyTracks" type="tracks"/>  
                    <!-- альбомы -->
                    <LastFive :items="spotifyAlbums" type="albums"/>  
                    <!-- исполнители -->
                    <LastFive :items="spotifyArtists" type="artists"/>  

                    <!-- часы и время -->
                    <HoursAndMinutes v-if="spotifyArtists !== -1 && spotifyAlbums != -1 && spotifyTracks != -1" 
                                    class="fade_in_slow_anim" :userLibraryTime="userLibraryTime"/>

                    <!-- самые длинные и короткие треки -->
                    <LongestAndShortest v-if="userLibraryTime !== -1" id="tracks"
                                        :fiveLongest="fiveTracks['fiveLongest']" 
                                        :fiveShortest="fiveTracks['fiveShortest']" :tracksMode="tracksMode"/>
                    <!-- любимые жанры -->
                    <FavoriteGenres v-if="fiveTracks !== -1" :favoriteGenres="favoriteGenres" id="genres"/>

                    <!-- кол-во исполнителей -->
                    <ArtistsCount v-if="favoriteGenres != -1" :uniqueArtists="uniqueArtists"/>

                    <!-- года и десятилетия -->
                    <YearsAndDecades v-if="uniqueArtists != -1" :yearsAndDecades="yearsAndDecades" type="alltime"/>

                    <!-- года и десятилетия за месяц-->
                    <YearsAndDecades v-if="yearsAndDecades != -1 && yearsAndDecadesMonth != false" :yearsAndDecades="yearsAndDecadesMonth" type="month"/>

                </div>     
            </div>
            <div v-else-if="spotifyUserLibrary['result'] == false">
                <Error errorMessage="Не удалось загрузить библиотеку пользователя"/>
            </div>
            <div v-else-if="spotifyUserLibrary['result'] == 'libraryError'">
                <Info :infoMessage="spotifyUserLibrary['errorMsg']"/>
            </div>
        </div>
        <br>
        <div class="row justify-content-center fade_in_anim" v-if="yearsAndDecadesMonth != -1">
            
            <router-link to="/profile/top10#top">
                <button class="btn btn-primary">
                    Перейти к "Топ-10"
                    <i class="fas fa-list-ol"></i>
                </button>
            </router-link>
            <br><br>
            
        </div>
    </div>

</template>

<script>
export default {
   
    mounted()
    {
        //прокручиваем страницу к якорю, если в url есть якорь
        var anchor=this.$router.currentRoute.hash.replace("#", "");

        if(anchor)
        { this.$nextTick(()=> window.document.getElementById(anchor).scrollIntoView()); }

        //устанавливаем текущий таб, для подсветки навигации
        this.$store.dispatch('setCurrentTab', 'basicStats');

        //получаем библиотеку пользователя, если она еще не загружена
        if(this.spotifyUserLibrary == -1)
        {
            //если запрос выполнился, то выполняем загружаем остальные данные, если нет, то не делаем ничего
            this.$store.dispatch('getSpotifyUserLibrary').then(response => {
                if(this.spotifyUserLibrary['result'] == true)
                {
                    this.getAllData();
                }
            }, error => {
                console.log("Error: Couldn't load user's Spotify library.");
            })
        }
        //загружаем остальные данные
        else
        { this.getAllData(); }
    },

    methods: {
        //получить все необходимые данные для этой страницы
        getAllData: function()
        {
            //получить треки
            if(this.spotifyTracks == -1)
            { this.$store.dispatch('getSpotifyTracks'); }

            //получить альбомы
            if(this.spotifyAlbums == -1)
            { this.$store.dispatch('getSpotifyAlbums'); }

            //получить артистов
            if(this.spotifyArtists == -1)
            { this.$store.dispatch('getSpotifyArtists'); }

            //получить общее кол-во часов\минут\дней музыки в библиотеке
            if(this.userLibraryTime == -1)
            { this.$store.dispatch('getUserLibraryTime'); }

            //пять самых длинных и коротких треков
            if(this.fiveTracks == -1)
            { this.$store.dispatch('getFiveLongestAndShortestTracks'); }

            //средняя длина трека
            if(this.tracksMode == -1)
            { this.$store.dispatch('getAverageLengthOfTrack'); }

            //любимые жанры
            if(this.favoriteGenres == -1)
            { this.$store.dispatch('getFavoriteGenres') };

            //кол-во исполнителей
            if(this.uniqueArtists == -1)
            { this.$store.dispatch('getUniqueArtists'); }

            //года и десятилетия
            if(this.yearsAndDecades == -1)
            { this.$store.dispatch('getYearsAndDecades', 'alltime'); }

            //года и десятилетия - месяц
            if(this.yearsAndDecadesMonth == -1)
            { this.$store.dispatch('getYearsAndDecades', 'month'); }
        },
    },
    
    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
        },
        //кол-во треков и последние пять
        spotifyTracks: function() {
            return this.$store.state.profilePage.spotifyTracks;
        },
        //кол-во альбомов и последние пять
        spotifyAlbums: function() {
            return this.$store.state.profilePage.spotifyAlbums;
        },
        //кол-во подписок и случайные пять
        spotifyArtists: function(){
            return this.$store.state.profilePage.spotifyArtists;
        },
        //время
        userLibraryTime: function(){
            return this.$store.state.profilePage.userLibraryTime;
        },
        //пять самых длинных
        fiveTracks: function(){
            return this.$store.state.profilePage.fiveTracks;
        },
        //средняя длина трека
        tracksMode: function() {
            return this.$store.state.profilePage.tracksMode;
        },
        //любимые жанры
        favoriteGenres: function(){
            return this.$store.state.profilePage.favoriteGenres;
        },
        //кол-во исполнителей
        uniqueArtists: function(){
            return this.$store.state.profilePage.uniqueArtists;
        },
        //года и десятилетия
        yearsAndDecades: function(){
            return this.$store.state.profilePage.yearsAndDecades;
        },
        //года и десятилетия
        yearsAndDecadesMonth: function(){
            return this.$store.state.profilePage.yearsAndDecadesMonth;
        },
    }
}
</script>