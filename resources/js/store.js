import Vuex from 'vuex'
import Vue from 'vue'

import axios from 'axios'
import VueAxios from 'vue-axios'
import { reject } from 'lodash'

Vue.use(Vuex)
Vue.use(VueAxios, axios);

const HomePageStates = {

    state: {
        welcomeMessage: -1, //welcome message
        cookiesVisible: -1, //куки видны
        spotifyUsername: -1, //никнейм пользователя, array
        spotifyUserTracksCount: -1, //подсчет треков, int
        siteInfo: -1, //общая информация о сайте, version, powered by и т.д
        about: -1, //информация о cайте, about
        faq: -1, //информация о сайте,  faq
        contacts: -1, //информация о сайте, контакты
        siteLogoUrl: -1, //ссылка на логотип сайта
        homePageImageUrl: -1, //ссылка на фоновую картинку для домашней страницы
        welcomeImageUrl: -1, //ссылка на картинку для приветствия
        recentTracks: -1, //последние прослушанные треки
      },

    mutations: {

        //уставноить cookiesVisible = false
        setCookiesVisibleFalse(state){
          state.cookiesVisible = false;
        },

        //получить ответ от API (универсальная mutation для всех стейтов)
        getAPIResponse(state, payload){
          axios.get(payload.uri).then((response) => {
            state[payload.state] = response.data;
          });
        },

        //установить стейт
        setState(state, payload){
          state[payload.state] = payload.value;
        }
    },
      
    actions: {

        //уставноить cookiesVisible = false
        setCookiesVisibleFalse(context)
        {
          context.commit('setCookiesVisibleFalse');
        },

        //установить cookiesAccepted
        //получить имя пользователя из API
        checkCookies(context){
          axios.get('/api/check_cookies').then(response => {
            // alert(response.data)
            if(response.data != false)
            { context.commit('setState', {state: 'cookiesVisible', value: true}); }
            else
            { context.commit('setState', {state: 'cookiesVisible', value: false}); }
          });
        },
        
        //установить cookiesAccepted
        //получить имя пользователя из API
        getWelcomeMessage(context){
          axios.get('/api/get_welcome_message').then(response => {
            // alert(response.data)
            if(response.data != false)
            { context.commit('setState', {state: 'welcomeMessage', value: response.data}); }
            else
            { context.commit('setState', {state: 'welcomeMessage', value: false}); }
          });
        },

        //получить имя пользователя из API
        getSpotifyUsername(context){
          axios.get('/api/get_spotify_username').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'spotifyUsername', value: response.data}); }
            else
            { context.commit('setState', {state: 'spotifyUsername', value: false}); }
          });
        },

        //получить количество треков в библиотеке пользователя
        getHomePageUserTracksCount(context){
          axios.get('/api/get_home_tracks_count').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'spotifyUserTracksCount', value: response.data}); }
            else
            { context.commit('setState', {state: 'spotifyUserTracksCount', value: false}); }
          });
        },
        
        //получить информацию о сайте
        getSiteInfo(context){
          axios.get('/api/get_site_info').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'siteInfo', value: response.data}); }
            else
            { context.commit('setState', {state: 'siteInfo', value: false}); }
          });
        },
   
        //получить информацию о сайте, About
        getAbout(context){
          axios.get('/api/get_about').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'about', value: response.data}); }
            else
            { context.commit('setState', {state: 'about', value: false}); }
          });
        },
      
        //получить информацию о сайте, FAQ
        getFAQ(context){
          axios.get('/api/get_faq').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'faq', value: response.data}); }
            else
            { context.commit('setState', {state: 'faq', value: false}); }
          });
        },

        //получить информацию о сайте, Контакты
        getContacts(context){
          axios.get('/api/get_contacts').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'contacts', value: response.data}); }
            else
            { context.commit('setState', {state: 'contacts', value: false}); }
          });
        },

        //получить лого
        getSiteLogoUrl(context){
          axios.get('/api/get_logo_img').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'siteLogoUrl', value: response.data}); }
            else
            { context.commit('setState', {state: 'siteLogoUrl', value: false}); }
          });
        },

        //получить картинку для приветствия
        getHomePageImageUrl(context){
          axios.get('/api/get_home_page_img').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'homePageImageUrl', value: response.data}); }
            else
            { context.commit('setState', {state: 'homePageImageUrl', value: false}); }
          });
        },

        //получить картинку приветствия
        getWelcomeImageUrl(context){
          axios.get('/api/get_welcome_img').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'welcomeImageUrl', value: response.data}); }
            else
            { context.commit('setState', {state: 'welcomeImageUrl', value: false}); }
          });
        },

        //последние прослушанные треки
        getLatestTracks(context){
          axios.get('/api/get_latest_tracks').then((response) => {
            if(response.data != false)
            { context.commit('setState', {state: 'recentTracks', value: response.data}); }
            else
            { context.commit('setState', {state: 'recentTracks', value: false}); }
          })
        },
    }
}

const ProfilePageStates = {
    state: {
        currentTab: - 1, //текущая вкладка на странице
        spotifyProfile: -1, //профиль пользователя, array
        spotifyUserLibrary: -1, //библиотека пользователя, bool
        spotifyTracks: -1, //кол-во треков и последние 5, array
        spotifyAlbums: -1, //кол-во альбомов и последние 5, array
        spotifyArtists: -1, //кол-во подписок и случайные 5, array
        userLibraryTime: -1, //общее время всех треков, array
        fiveTracks: -1, //пять самых длинных и коротких треков, array
        tracksMode: -1, //средняя длина трека, string
        profileBackgroundUrl: -1, //фон для профиля
        favoriteGenres: -1, //любимые жанры
        uniqueArtists: -1, //кол-во исполнителей,
        yearsAndDecades: -1, //года и десятилетия
        yearsAndDecadesMonth: -1, //года и десятилетия за месяц

        top10TracksAllTime: -1, //топ 10 треков за все время
        top10TracksMonth: -1, // топ 10 треков за месяц
        top10TracksLong: -1, //топ 10 длинных треков
        top10TracksShort: -1, //топ 10 коротких треков
        top10PopularTracks: -1, //топ 10 популярных треков
        top10UnpopularTracks: -1, //топ 10 непопулярных треков
        top10ArtistsAllTime: -1, //топ 10 исполнителей за все время
        top10ArtistsMonth: -1, //топ 10 исполнителей за месяц
        top10ArtistsByTracks: -1, //топ 10 исполнителей по кол-ву треков
        top10ArtistsByTime: -1, //топ 10 исполнителей по времени треков

        mostListenedTrack: -1, //самый прослушиваемый трек за все время
        mostListenedTrackMonth: -1, //самый прослушиваемый трек за месяц
        mostPopularTrack: -1, //самый популярный трек в библиотеке
        leastPopularTrack: -1, //самый непопулярный трек в библиотеке
        longestTrack: -1, //самый длинный трек
        shortestTrack: -1, //самый короткий трек
        mostListenedArtist: -1, //самый слушаемый артист
        mostListenedArtistMonth: -1, //самый слушаемый артист за месяц
        topArtistByTracks: -1, //артист с наибольшим кол-вом треков
        topArtistByTime: -1, //артист с наибольшим кол-вом временем треков
        mostPopularArtist: -1, //самый популярный артист, из подписок
        leastPopularArtist: -1, //самый непопулярный артист, из подписок
      },

    mutations: {
          //установить текущую вкладку
          setCurrentTab(state, tab){
            state.currentTab = tab;
          },
          //"легкие" запросы отправляются через getAPIResponse, "тяжелые" через свои собственные мутации
          //получить ответ от API (универсальная mutation для (почти) всех стейтов)
          getAPIResponse(state, payload){
            axios.get(payload.uri).then((response) => {
              state[payload.state] = response.data;
            });
          },

          //установить стейт
          setState(state, payload){
            state[payload.state] = payload.value;
          }
},

    actions: {

      //общее
      //установить текущую вкладку
      setCurrentTab(context, tab){
        context.commit('setCurrentTab', tab);
      },

      //получить профиль
      getSpotifyProfile(context){
          axios.get("/api/get_spotify_profile").then(response => {
              context.commit('setState', {state: "spotifyProfile", value: response.data});
          }, error => {
            context.commit('setState', {state: "spotifyProfile", value: false});
            reject(error);
          });
      },

      //получить библиотку пользователя
      getSpotifyUserLibrary(context){
          return new Promise((resolve, reject) => {
              axios.get('/api/get_spotify_user_library').then(response => {
                  context.commit('setState', {state: 'spotifyUserLibrary', value: response.data});
                  resolve(response);  
              }, error => {
                  context.commit('setState', {state: 'spotifyUserLibrary', value: false});
                  reject(error);
              });
          });
      },
      
      //получить кол-во треков в библиотеке и последние пять
      getSpotifyTracks(context){
          axios.get('/api/get_spotify_tracks').then(response => {
            if(response.data != false)
            { context.commit('setState', {state: 'spotifyTracks', value: response.data}); }
            else
            { context.commit('setState', {state: 'spotifyTracks', value: false}); }    
          });    
      },

      //получить кол-во альбомов в библиотеке и последние пять
      getSpotifyAlbums(context){
        axios.get('/api/get_spotify_albums').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'spotifyAlbums', value: response.data}); }
          else
          { context.commit('setState', {state: 'spotifyAlbums', value: false}); }
        });
      }, 

      //получить кол-во подписок в библиотеке и случайные пять
      getSpotifyArtists(context){
        axios.get('/api/get_spotify_artists').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'spotifyArtists', value: response.data}); }
          else
          { context.commit('setState', {state: 'spotifyArtists', value: false}); }
        });
      },

      //посчитать кол-во времени
      getUserLibraryTime(context){
        axios.get('/api/get_user_library_time').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'userLibraryTime', value: response.data}); }
          else
          { context.commit('setState', {state: 'userLibraryTime', value: false}); }
        });
      },

      //пять самых длинных и коротких треков
      getFiveLongestAndShortestTracks(context){
        axios.get('/api/get_five_tracks').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'fiveTracks', value: response.data}); }
          else
          { context.commit('setState', {state: 'fiveTracks', value: false}); }
        });
      },

      //средняя длина трека
      getAverageLengthOfTrack(context){
        axios.get('/api/get_average_track_length').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'tracksMode', value: response.data}); }
          else
          { context.commit('setState', {state: 'tracksMode', value: false}); }
        });
      },

      //получить любимые жанры
      getFavoriteGenres(context){
        axios.get('/api/get_favorite_genres').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'favoriteGenres', value: response.data}); }
          else
          { context.commit('setState', {state: 'favoriteGenres', value: false}); }
        });
      },

      //кол-во исполнителей
      getUniqueArtists(context){
        axios.get('/api/get_unique_artists').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'uniqueArtists', value: response.data}); }
          else
          { context.commit('setState', {state: 'uniqueArtists', value: false}); }
        });
      },

      //посчитать года и десятилетия за все время или месяц
      getYearsAndDecades(context, type){
        var stateName = "yearsAndDecades";
        if(type === "month")
        { stateName = "yearsAndDecadesMonth" };

        axios.get('/api/get_years_and_decades/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },


      //топ10
      //топ10 треков за все время или за месяц
      getTop10Tracks(context, type){
        var stateName = "top10TracksAllTime";
        if(type === "month")
        { stateName = "top10TracksMonth" };

        axios.get('/api/get_top10_tracks/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },

      //топ 10 длинных треков
      getTop10TracksByLength(context, type){
        var stateName = "top10TracksLong";
        if(type === "short")
        { stateName = "top10TracksShort" };

        axios.get('/api/get_top10_tracks_by_length/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },

      //топ 10 коротких треков
      getTop10TracksByPopularity(context, type){
        var stateName = "top10PopularTracks";
        if(type === "unpopular")
        { stateName = "top10UnpopularTracks" };

        axios.get('/api/get_top10_tracks_by_popularity/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },

      //топ 10 исполнителей за все время или за месяц
      getTop10Artists(context, type){
        var stateName = "top10ArtistsAllTime";
        if(type === "month")
        { stateName = "top10ArtistsMonth" };

        axios.get('/api/get_top10_artists/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },

      //топ 10 исполнителей по кол-ву треков
      getTop10ArtistsByTracks(context){
        axios.get('/api/get_top10_artists_by_tracks').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'top10ArtistsByTracks', value: response.data}); }
          else
          { context.commit('setState', {state: 'top10ArtistsByTracks', value: false}); }
        });
      },

      //топ 10 исполнителей по времени треков
      getTop10ArtistsByTime(context){
        axios.get('/api/get_top10_artists_by_time').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'top10ArtistsByTime', value: response.data}); }
          else
          { context.commit('setState', {state: 'top10ArtistsByTime', value: false}); }
        });
      },

      //ачивки
      //самый прослушиваемый трек за все время
      getMostListenedTrack(context, type){
        var stateName = "mostListenedTrack";
        if(type === "month")
        { stateName = "mostListenedTrackMonth" };

        axios.get('/api/get_most_listened_track/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },


      //самый популярный трек
      getTrackByPopularity(context, type){
        var stateName = "mostPopularTrack";
        if(type === "unpopular")
        { stateName = "leastPopularTrack" };

        axios.get('/api/get_track_by_popularity/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },

 
      //самый длинный трек
      getTrackByDuration(context, type){
        var stateName = "longestTrack";
        if(type === "short")
        { stateName = "shortestTrack" };

        axios.get('/api/get_track_by_duration/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },


      //самый слушаемый артист
      getMostListenedArtist(context, type){
        var stateName = "mostListenedArtist";
        if(type === "month")
        { stateName = "mostListenedArtistMonth" };

        axios.get('/api/get_most_listened_artist/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },


      //артист с наибольшим кол-вом треков
      getArtistByTracks(context){
        axios.get('/api/get_artist_by_tracks').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'topArtistByTracks', value: response.data}); }
          else
          { context.commit('setState', {state: 'topArtistByTracks', value: false}); }
        });
      },

      //артист с наибольшим кол-вом времени треков
      getArtistByTime(context){
        axios.get('/api/get_artist_by_time').then(response => {
          if(response.data != false)
          { context.commit('setState', {state: 'topArtistByTime', value: response.data}); }
          else
          { context.commit('setState', {state: 'topArtistByTime', value: false}); }
        });
      },

      //самый популярный артист, из подписок
      getArtistByPopularity(context, type){
        var stateName = "mostPopularArtist";
        if(type === "unpopular")
        { stateName = "leastPopularArtist" };

        axios.get('/api/get_artist_by_popularity/'+ type).then(response => {
          if(response.data != false)
          { context.commit('setState', {state: stateName, value: response.data}); }
          else
          { context.commit('setState', {state: stateName, value: false}); }
        });
      },


  }
}

export default new Vuex.Store({
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }
});