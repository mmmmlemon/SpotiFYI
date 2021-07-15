//Top10
<template>
   <div id="top10">
        <div class="row justify-content-center">
            <div class="col-12" v-if="spotifyUserLibrary == -1">
                <Loader />
                <h6 class="text-center blinkingAnim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
                <p class="text-center">Это может занять около минуты</p>
            </div>
            <div v-else-if="spotifyUserLibrary != -1 && spotifyUserLibrary['result'] != false" class="row justify-content-center">
                <!-- навигация -->
                <!-- <div class="row justify-content-center fadeInAnim" style="margin-top:5%;">
                    <nav class="justify-content-center">
                        <ul class="breadcrumb text-center">
                            <li class="breadcrumb-item"><a href="#tracks">Треки</a></li>
                            <li class="breadcrumb-item"><a href="#artists">Исполнители</a></li>
                        </ul>
                    </nav>
                </div> -->

                <!-- топ 10 треки -->
                <div class="col-12 justify-content-center goUpAnimSlow" id="tracks">
                    <h3 class="text-center">
                        Треки
                        <i class="fas fa-compact-disc primaryColor"></i>
                    </h3>
                </div>
            
                <Top10Items v-if="top10TracksAllTime != 'noTracks'" 
                            cardTitle="Топ 10 Треков за все время" 
                            cardDesc="Десять самых прослушиваемых треков за все время" 
                            :items="top10TracksAllTime"
                            listType="tracks" orientation="left" :visibleProp="true"
                            desc="К этим песням ты возвращаешься чаще всего"/>

                <Top10Items v-if="top10TracksMonth != 'noTracks' && top10TracksAllTime != -1"
                            loaderMessage="Загружаю Топ 10 треков за месяц..."
                            cardTitle="Топ 10 Треков за месяц" 
                            cardDesc="Десять самых прослушиваемых треков за последний месяц" 
                            :items="top10TracksMonth"
                            listType="tracks"
                            desc="Эти песни не покидают тебя весь последний месяц"/>

                <Top10Items v-if="top10TracksMonth != -1"
                            cardTitle="Топ 10 самых длинных" 
                            cardDesc="Десять самых длинных треков в библиотеке" 
                            :items="top10TracksLong"
                            listType="tracks"
                            desc="Самые длиииииииииииииинные песни которые тебе нравятся"/> 

                <Top10Items v-if="top10TracksLong != -1"
                            cardTitle="Топ 10 самых коротких" 
                            cardDesc="Десять твоих самых коротких треков в библиотеке" 
                            :items="top10TracksShort"
                            listType="tracks"
                            desc="Тви лбмые крткие псни"/>
                <Top10Items v-if="top10TracksShort != -1"
                            cardTitle="Топ 10 самых популярных" 
                            cardDesc="Десять самых популярных треков которые тебе нравятся" 
                            :items="top10PopularTracks"
                            listType="tracks"
                            desc="Кроме тебя эти песни нравятся еще много кому"/>
                <Top10Items v-if="top10PopularTracks != -1"
                            cardTitle="Топ 10 самых непопулярных" 
                            cardDesc="Десять самых непопулярных треков которые тебе нравятся" 
                            :items="top10UnpopularTracks"
                            listType="tracks"
                            desc="Кроме тебя, похоже, эти песни больше никто не слушает"/>

                <!-- топ 10 исполнители -->
                <div class="col-12 justify-content-center" id="artists" v-if="top10UnpopularTracks != -1" v-scroll="handleScrollArtists" v-bind:class="{'zeroOpacity': visibleArtists === false}">
                    <h3 class="text-center goUpAnimSlow" v-if="visibleArtists">
                        Исполнители
                        <i class="fas fa-users primaryColor"></i>
                    </h3>
                </div>

                <Top10Items v-if="top10UnpopularTracks != -1 && top10ArtistsAllTime != 'noArtists'"
                            cardTitle="Топ 10 артистов за все время" 
                            cardDesc="Десять твоих самых прослушиваемых артистов за все время" 
                            :items="top10ArtistsAllTime"
                            listType="artists"
                            desc="Эти группы и артисты никогда тебя не покидают"/>

                <Top10Items v-if="top10ArtistsAllTime != -1 && top10ArtistsMonth != 'noArtists'"
                            cardTitle="Топ 10 артистов за месяц" 
                            cardDesc="Десять твоих самых прослушиваемых артистов за последний месяц" 
                            :items="top10ArtistsMonth"
                            listType="artists"
                            desc="Эти группы и артисты были с тобой последний месяц"/>

                <Top10Items v-if="top10ArtistsMonth != -1"
                            cardTitle="Топ 10 артистов по трекам" 
                            cardDesc="Десять артистов с наибольшим кол-вом треков в твоей библиотеке" 
                            :items="top10ArtistsByTracks"
                            listType="artists"
                            desc="Твои любимчики по количеству добавленных треков"/>
                <Top10Items v-if="top10ArtistsByTracks != -1"
                            cardTitle="Топ 10 артистов по времени треков" 
                            cardDesc="Десять артистов с наибольшим кол-вом часов музыки в твоей библиотеке" 
                            :items="top10ArtistsByTime"
                            listType="artists"
                            desc="Твои любимчики по количеству часов музыки"/>
            </div>
            <div v-else-if="spotifyUserLibrary == false">
                <Error errorMessage="Не удалось загрузить библиотеку пользователя"/>
            </div>
            <div v-else>
                <Info :infoMessage="spotifyUserLibrary['errorMsg']"/>
            </div>
            
        </div>
        <br>
        <div class="row justify-content-center" style="margin-top: 2rem;" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visibleButton === false}">
            <router-link to="/profile#basic">
                <button class="btn btn-lg btn-primary-n goUpAnimSlow" v-if="visibleButton === true && top10ArtistsByTime != -1">
                    Перейти к <b>Общее</b>
                </button>
            </router-link>
            <br><br><br><br><br><br>
            
        </div>
   </div>
</template>

<script>
export default {

    created(){
        this.checkToken().then(response => {
            if(response === 'refresh'){
                alert('refresh')
                var url = window.location.href;
                var indexOfAnchor = url.indexOf('#');
                if(indexOfAnchor != -1)
                {var url = url.slice(0, indexOfAnchor);}
                axios.get('/refresh_token').then(response => {
                    if(response.data = true){
                        
                        window.location.replace(url);
                    }
                });

            } else{
                alert(response);
                //получаем библиотеку пользователя, если она еще не загружена
                //получаем библиотеку пользователя и статистику
                if(this.spotifyUserLibrary == -1)
                {
                    //если запрос выполнился, то выполняем все остальные, если нет, то не делаем ничего
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
            }
        });
    },

    mounted(){
        //прокручиваем страницу к якорю, если в url есть якорь
        var anchor=this.$router.currentRoute.hash.replace("#", "");
        
        if(anchor)
        {
            var el = document.getElementById(anchor);
        
            if(el != null)
            { this.$nextTick(()=> window.document.getElementById(anchor).scrollIntoView()); }
        }

        //смена текущего таба
        this.$store.dispatch('setCurrentTab','top10');

   
  
    },

    
    data: ()=> {
        return {
            visibleButton: false,
            visibleArtists: false,
        }
    },

    methods: {
        getAllData: function(){
            //топ 10 треков за всё время
            if(this.top10TracksAllTime == -1)
            { this.$store.dispatch('getTop10Tracks', 'alltime'); }

            //топ 10 треков за месяц
            if(this.top10TracksAllTime == -1)
            { this.$store.dispatch('getTop10Tracks', 'month'); }

            //топ 10 длинных треков
            if(this.top10TracksLong == -1)
            { this.$store.dispatch('getTop10TracksByLength', 'long'); }

            //топ 10 коротких треков
            if(this.top10TracksShort == -1)
            { this.$store.dispatch('getTop10TracksByLength', 'short'); }

            //топ 10 популярных треков
            if(this.top10PopularTracks == -1)
            { this.$store.dispatch('getTop10TracksByPopularity', 'popular'); }

            //топ 10 непопулярных треков
            if(this.top10UnpopularTracks == -1)
            { this.$store.dispatch('getTop10TracksByPopularity', 'unpopular'); }

            //топ 10 артистов за все время
            if(this.top10ArtistsAllTime == -1)
            { this.$store.dispatch('getTop10Artists', 'alltime'); }

            //топ 10 артистов за месяц
            if(this.top10ArtistsMonth == -1)
            { this.$store.dispatch('getTop10Artists', 'month'); }

            //топ 10 артистов по кол-ву треков
            if(this.top10ArtistsByTracks == -1)
            { this.$store.dispatch('getTop10ArtistsByTracks'); }

            //топ 10 артистов по кол-ву времени
            if(this.top10ArtistsByTime == -1)
            { this.$store.dispatch('getTop10ArtistsByTime'); }     
        },
        //при скролле страницы показать карточку когда она будет 
        //в поле видимости
        handleScroll: function (evt, el){
            if (el.getBoundingClientRect().top < 900) {
                this.setVisible = true;
            }
            return el.getBoundingClientRect().top < 900;   
        },
        handleScrollArtists: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setVisibleArtists = true;
            }
            return el.getBoundingClientRect().top < 700;   
        }
    },

    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
            // return true;
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
        top10PopularTracks: function(){
            return this.$store.state.profilePage.top10PopularTracks;
        },
        top10UnpopularTracks: function(){
            return this.$store.state.profilePage.top10UnpopularTracks;
        },
        top10ArtistsByTracks: function() {
            return this.$store.state.profilePage.top10ArtistsByTracks;
        },
        top10ArtistsByTime: function() {
            return this.$store.state.profilePage.top10ArtistsByTime;
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

        //видимость карточки
        setVisibleArtists: {
            get() {
                this.visibleArtists = false;
            },
            set(value){
                this.visibleArtists = value;
            }
        },
    }
}
</script>