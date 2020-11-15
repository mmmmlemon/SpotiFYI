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
        getSpotifyUserTracksCount(state){
            let uri = '/api/get_spotify_tracks_count';
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
        getSpotifyUserTracksCount(context){
            context.commit('getSpotifyUserTracksCount');
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
        spotifyTrackCount: -1,
        spotifyAlbumCount: -1,
      },

    getters:{
      //геттеры
    },

    mutations: {
        //получить профиль
        getSpotifyProfile(state){
          let uri = '/api/get_spotify_profile';
          axios.get(uri).then((response) => {
            state.spotifyProfile = response.data;
          });
        },
        //получить кол-во треков в библиотеке
        getSpotifyTrackCount(state){
          let uri = '/api/get_spotify_track_count';
          axios.get(uri).then((response) => {
            state.spotifyTrackCount = response.data;
          });
        },
        //получить кол-во альбомов в библиотеке
        getSpotifyAlbumCount(state){
          let uri = '/api/get_spotify_album_count';
          axios.get(uri).then((response) => {
            state.spotifyAlbumCount = response.data;
           });
        },
    },

    actions: {
      //получить профиль
      getSpotifyProfile(context){
        context.commit('getSpotifyProfile');
      },
      //получить кол-во треков в библиотеке
      getSpotifyTrackCount(context){
        context.commit('getSpotifyTrackCount');
      },
       //получить кол-во альбомов в библиотеке
       getSpotifyAlbumCount(context){
         context.commit('getSpotifyAlbumCount');
       }
    }
}

export default new Vuex.Store({
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }
});