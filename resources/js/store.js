import Vuex from 'vuex'
import Vue from 'vue'

import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(Vuex)
Vue.use(VueAxios, axios);

const HomePageStates = {
    state: {
        loggedIn: -1,
        spotifyUsername: false,
        spotifyUserTracksCount: -1,
        siteInfo: false,
      },

      getters: {
        //геттеры
      },
      
      mutations: {
        //получить имя пользователя из API
        getSpotifyUsername(state){
            let uri = '/api/get_spotify_username';
            axios.get(uri).then((response) => {
                state.loggedIn = response.data.loggedIn;
                if(response.data.spotifyUsername != undefined)
                {
                    state.spotifyUsername = response.data.spotifyUsername;
                }
            })
        },
        //получить количество треков в библиотеке пользователя
        getHomePageUserTracksCount(state){
            let uri = '/api/get_home_tracks_count';
            axios.get(uri).then((response) => {
                state.spotifyUserTracksCount = response.data;
            });
        },
        //получить информацию о сайте
        getSiteInfo(state){
          let uri ='/api/get_site_info';
          axios.get(uri).then((response) => {
            state.siteInfo = response.data;
          });
        }
      },
      
      actions: {
        //получить имя пользователя из API
        getSpotifyUsername(context){
            context.commit('getSpotifyUsername');
        },
        //получить количество треков в библиотеке пользователя
        getHomePageUserTracksCount(context){
            context.commit('getHomePageUserTracksCount');
        },
        //получить информацию о сайте
        getSiteInfo(context){
          context.commit('getSiteInfo');
        },
      }
}

const ProfilePageStates = {
    state: {
        spotifyProfile: -1,
        spotifyUserLibrary: -1,
        spotifyTracks: -1,
        spotifyAlbums: -1,
        spotifyArtists: -1,
        userLibraryTime: -1,
        fiveTracks: -1,
        tracksMode: -1,
      },

    getters:{
      //геттеры
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