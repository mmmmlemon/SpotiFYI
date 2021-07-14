//BasicStats
<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-12" v-if="spotifyUserLibrary == -1">
                <Loader />
                <h6 class="text-center blinkingAnim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
                <h6 class="text-center blinkingAnim" v-if="spotifyUserLibrary == true">Анализирую треки...</h6>
                <p class="text-center font10pt">Это может занять около минуты</p>
            </div>
            <div v-else-if="spotifyUserLibrary != -1 && spotifyUserLibrary['result'] != false 
                && spotifyUserLibrary['result'] != 'libraryError'" class="row justify-content-center">
                <!-- навигация -->
                <!-- <div class="row justify-content-center fadeInAnim" style="margin-top:5%;">
                    <nav class="justify-content-center">
                        <ul class="breadcrumb text-center">
                            <li class="breadcrumb-item"><a href="#basic">Общее</a></li>
                            <li class="breadcrumb-item"><a href="#genres">Жанры и годы</a></li>
                        </ul>
                    </nav>
                </div> -->

                <div class="col-12 justify-content-center fadeInAnim">
                </div>
            
                <div class="row justify-content-center" id="basic">
                    <!-- треки -->
                    <LastFive :items="spotifyTracks" type="tracks"/>  
                    <!-- альбомы -->
                    <LastFive :items="spotifyAlbums" type="albums" v-if="spotifyTracks != -1"/>  
                    <!-- исполнители -->
                    <LastFive :items="spotifyArtists" type="artists" v-if="spotifyAlbums != -1"/>  

                    <!-- часы и время -->
                    <HoursAndMinutes v-if="spotifyArtists !== -1 && spotifyAlbums != -1 && spotifyTracks != -1" 
                                     :userLibraryTime="userLibraryTime"/>

                    <!-- самые длинные и короткие треки -->
                    <AverageTrackLength v-if="userLibraryTime !== -1" id="tracks"
                                        :tracksMode="tracksMode"/>
                    
                    <!-- кол-во исполнителей -->
                    <ArtistsCount v-if="tracksMode != -1" :uniqueArtists="uniqueArtists"/>

                    <!-- года и десятилетия -->
                    <YearsAndDecades :yearsAndDecades="yearsAndDecades" type="alltime" v-if="uniqueArtists != -1"/>

                    <!-- года и десятилетия за месяц-->
                    <YearsAndDecades v-if="uniqueArtists != -1" :yearsAndDecades="decadeMonth" type="month"/>

                    <!-- любимые жанры -->
                    <FavoriteGenres v-if="decadeMonth != -1" :favoriteGenres="favoriteGenres" id="genres"/>

                    <!-- Самый популярный\непопулярный артист -->
                    <AchievementItem v-if="mostPopularArtist != 'noArtists' && favoriteGenres != -1" 
                                cardTitle="Самый популярный исполнитель" cardSubtitle="На которого ты подписан" 
                                :items="mostPopularArtist"/>

                    <AchievementItem v-if="mostPopularArtist != -1 && leastPopularArtist != 'noArtists'" 
                                    cardTitle="Самый непопулярный исполнитель" cardSubtitle="На которого ты подписан" 
                                    :items="leastPopularArtist" orientation="right"/>
                                    
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
        <div class="row justify-content-center" style="margin-top: 2rem;" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visibleButton === false}">
            <router-link to="/profile/top10#top">
                <button class="btn btn-lg btn-primary-n goUpAnimSlow" v-if="visibleButton && leastPopularArtist != -1">
                    Перейти к <b>Топ-10</b>
                </button>
            </router-link>
            <br><br><br><br><br><br>
            
        </div>
        <div class="col-12">&nbsp;&nbsp;&nbsp;</div>
        <div class="col-12">&nbsp;&nbsp;&nbsp;</div>
    </div>

</template>

<script>
export default {
   
    created(){
        this.checkToken().then(response => {
            if(response === 'refresh'){
                alert('refresh')
               var url = window.location.href;
               
                    axios.get('/refresh_token').then(response => {
                        if(response.data = true){
                            
                            window.location.replace(url);
                        }
                    });

            } else{
                alert(response);
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
            }
        });
    },

    mounted()
    {
        //прокручиваем страницу к якорю, если в url есть якорь
        var anchor=this.$router.currentRoute.hash.replace("#", "");
        
        if(anchor)
        {
            var el = document.getElementById(anchor);
        
            if(el != null)
            { this.$nextTick(()=> window.document.getElementById(anchor).scrollIntoView()); }
        }

        //устанавливаем текущий таб, для подсветки навигации
        this.$store.dispatch('setCurrentTab', 'basicStats');
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

            //средняя длина трека
            if(this.tracksMode == -1)
            { this.$store.dispatch('getAverageLengthOfTrack'); }

            //кол-во исполнителей
            if(this.uniqueArtists == -1)
            { this.$store.dispatch('getUniqueArtists'); }

            //года и десятилетия
            if(this.yearsAndDecades == -1)
            { this.$store.dispatch('getYearsAndDecades'); }

            //года и десятилетия - месяц
            if(this.decadeMonth == -1)
            { this.$store.dispatch('getDecadeMonth'); }

            //любимые жанры
            if(this.favoriteGenres == -1)
            { this.$store.dispatch('getFavoriteGenres') };

            //cамый популярный артист, из подписок
            if(this.mostPopularArtist == -1)
            { this.$store.dispatch('getArtistByPopularity', 'popular'); }

            //cамый непопулярный артист, из подписок
            if(this.mostPopularArtist == -1)
            { this.$store.dispatch('getArtistByPopularity', 'unpopular'); }
        },

        //при скролле страницы показать карточку когда она будет 
        //в поле видимости
        handleScroll: function (evt, el){
            if (el.getBoundingClientRect().top < 900) {
                this.setVisible = true;
            }
            return el.getBoundingClientRect().top < 900;   
        }
    },

    data: ()=> {
        return {
            visibleButton: false,
        }
    },
    
    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
            // return true; 
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
        decadeMonth: function(){
            return this.$store.state.profilePage.decadeMonth;
        },
        //самый популярный артист
        mostPopularArtist: function(){
            return this.$store.state.profilePage.mostPopularArtist;
        },
        //самый непопулярный артист
        leastPopularArtist: function(){
            return this.$store.state.profilePage.leastPopularArtist;
        },

        //видимость карточки
        setVisible: {
            get() {
                this.visibleButton = false;
            },
            set(value){
                this.visibleButton = value;
            }
        },
    }
}
</script>