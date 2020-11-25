import Vuex from 'vuex'
import Vue from 'vue'

import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(Vuex)
Vue.use(VueAxios, axios);

const HomePageStates = {

    state: {
        spotifyLogInInfo: false, //никнейм пользователя, array
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
            context.commit('getAPIResponse', {state: "spotifyLogInInfo", uri: '/api/get_spotify_username'});
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
        spotifyProfile: -1, //профиль пользователя, array
        spotifyUserLibrary: -1, //бибилотека пользователя, bool
        spotifyTracks: -1, //кол-во треков и последние 5, array
        spotifyAlbums: -1, //кол-во альбомов и последние 5, array
        spotifyArtists: -1, //кол-во подписок и случайные 5, array
        userLibraryTime: -1, //общее время всех треков, array
        fiveTracks: -1, //пять самых длинных и коротких треков, array
        tracksMode: -1, //средняя длина трека, string
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
      //получить профиль
      getSpotifyProfile(context){
        context.commit('getAPIResponse', {state: "spotifyProfile", uri: '/api/get_spotify_profile'});
      },
      //получить библиотку пользователя
      getSpotifyUserLibrary(context){
        context.commit('getAPIResponse', {state: "spotifyUserLibrary", uri: '/api/get_spotify_user_library'});
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
      }
    }
}

export default new Vuex.Store({
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }
});