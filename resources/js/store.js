import Vuex from 'vuex'
import Vue from 'vue'

import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(Vuex)
Vue.use(VueAxios, axios);

const HomePageStates = {

    state: {
        spotifyUsername: false, //никнейм пользователя, array
        spotifyUserTracksCount: -1, //подсчет треков, int
        siteInfo: false, //информация о сайта для страницы About, array
      },

    mutations: {
        //получить ответ от API (универсальная mutation для всех стейтов)
        getAPIResponse(state, payload){
          axios.get(payload.uri).then((response) => {
            state[payload.state] = response.data;
          });
        },
    },
      
    actions: {
        //получить имя пользователя из API
        getSpotifyUsername(context){
            context.commit('getAPIResponse', {state: "spotifyUsername", uri: '/api/get_spotify_username'});
        },
        //получить количество треков в библиотеке пользователя
        getHomePageUserTracksCount(context){
            context.commit('getAPIResponse', {state: "spotifyUserTracksCount", uri: '/api/get_home_tracks_count'});
        },
        //получить информацию о сайте
        getSiteInfo(context){
          context.commit('getAPIResponse', {state: "siteInfo", uri: '/api/get_site_info'});
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

        top10TracksAllTime: -1, //топ 10 треков за все время
        top10TracksMonth: -1, // топ 10 треков за месяц
        top10ArtistsAllTime: -1, //топ 10 исполнителей за все время
        top10ArtistsMonth: -1, //топ 10 исполнителей за месяц
        top10TracksLong: -1, //топ 10 длинных треков
        top10TracksShort: -1, //топ 10 коротких треков
        top10ArtistsByTracks: -1, //топ 10 исполнителей по кол-ву треков
        top10ArtistsByTime: -1, //топ 10 исполнителей по времени треков
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
          getSpotifyProfile(state){
            axios.get('/api/get_spotify_profile').then((response) => {
              state.spotifyProfile = response.data;
            });
          },
          //получить библиотеку пользователя
          getSpotifyUserLibrary(state){
            axios.get('/api/get_spotify_user_library').then((response) => {
              state.spotifyUserLibrary = response.data;
            });
          },
          //получить любимые жанры
          getFavoriteGenres(state){
              axios.get('/api/get_favorite_genres/').then((response) => {
                state.favoriteGenres = response.data;
              });
          },
    },

    actions: {
      //общее
      //установить текущую вкладку
      setCurrentTab(context, tab){
        context.commit('setCurrentTab', tab);
      },
      //получить профиль
      getSpotifyProfile(context){
        context.commit('getSpotifyProfile');
      },
      //получить библиотку пользователя
      getSpotifyUserLibrary(context){
        context.commit('getSpotifyUserLibrary');
      },
      //получить кол-во треков в библиотеке и последние пять
      getSpotifyTracks(context){
        context.commit('getAPIResponse', {state: "spotifyTracks", uri: '/api/get_spotify_tracks'});
      },
      //получить кол-во альбомов в библиотеке и последние пять
      getSpotifyAlbums(context){
        context.commit('getAPIResponse', {state: "spotifyAlbums", uri: '/api/get_spotify_albums'});
      }, 
      //получить кол-во подписок в библиотеке и случайные пять
      getSpotifyArtists(context){
        context.commit('getAPIResponse', {state: "spotifyArtists", uri: '/api/get_spotify_artists'});
      },
      //посчитать кол-во времени
      getUserLibraryTime(context){
        context.commit('getAPIResponse', {state: "userLibraryTime", uri: '/api/get_user_library_time'});
      },
      //пять самых длинных
      getFiveLongestAndShortestTracks(context){
        context.commit('getAPIResponse', {state: "fiveTracks", uri: '/api/get_five_tracks'});
      },
      //средняя длина трека
      getAverageLengthOfTrack(context){
        context.commit('getAPIResponse', {state: "tracksMode", uri: '/api/get_average_track_length'});
      },
      //получить любимые жанры
      getFavoriteGenres(context){
        context.commit('getFavoriteGenres');
      },
      //кол-во исполнителей
      getUniqueArtists(context){
        context.commit('getAPIResponse', {state:"uniqueArtists", uri: '/api/get_unique_artists'});
      },
      //посчитать года и десятилетия
      getYearsAndDecades(context){
        context.commit('getAPIResponse', {state:'yearsAndDecades', uri: '/api/get_years_and_decades'});
      },

      //топ10
      //топ10 треков за все время
      getTop10TracksAllTime(context){
        context.commit('getAPIResponse', {state: 'top10TracksAllTime', uri: '/api/get_top10_tracks/alltime'});
      },
      //топ10 треков за все время
      getTop10TracksMonth(context){
        context.commit('getAPIResponse', {state: 'top10TracksMonth', uri: '/api/get_top10_tracks/month'});
      },
      //топ10 исполнителей за все время
      getTop10ArtistsAllTime(context){
        context.commit('getAPIResponse', {state: 'top10ArtistsAllTime', uri: '/api/get_top10_artists/alltime'});
      },
      //топ10 исполнителей за все время
      getTop10ArtistsMonth(context){
        context.commit('getAPIResponse', {state: 'top10ArtistsMonth', uri: '/api/get_top10_artists/month'});
      },
      //топ 10 длинных треков
      getTop10TracksLong(context){
        context.commit('getAPIResponse', {state: 'top10TracksLong', uri: '/api/get_top10_tracks_by_length/long'});
      },
      //топ 10 коротких треков
      getTop10TracksShort(context){
        context.commit('getAPIResponse', {state: 'top10TracksShort', uri: '/api/get_top10_tracks_by_length/short'});
      },
      //топ 10 исполнителей по кол-ву треков
      getTop10ArtistsByTracks(context){
        context.commit('getAPIResponse', {state: 'top10ArtistsByTracks', uri: '/api/get_top10_artists_by_tracks'});
      },
      //топ 10 исполнителей по времени треков
      getTop10ArtistsByTime(context){
        context.commit('getAPIResponse', {state: 'top10ArtistsByTime', uri: '/api/get_top10_artists_by_time'});
      },
  }
}

export default new Vuex.Store({
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }
});