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
        fiveLongest: -1,
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
        //получить библиотеку пользователя
        getSpotifyUserLibrary(state){
          let uri = '/api/get_spotify_user_library';
          axios.get(uri).then((response) => {
            state.spotifyUserLibrary = response.data;
          });
        },
        //получить кол-во треков в библиотеке и последние пять
        getSpotifyTracks(state){
          let uri = '/api/get_spotify_tracks';
          axios.get(uri).then((response) => {
            state.spotifyTracks = response.data;
          });
        },
        //получить кол-во альбомов в библиотеке и последние пять
        getSpotifyAlbums(state){
          let uri = '/api/get_spotify_albums';
          axios.get(uri).then((response) => {
            state.spotifyAlbums = response.data;
          });
        },
        //получить кол-во подписок в библиотеке и случайные пять
        getSpotifyArtists(state){
        let uri = '/api/get_spotify_artists';
        axios.get(uri).then((response) => {
          state.spotifyArtists = response.data;
        });
      },
      //посчитать кол-во времени
      getUserLibraryTime(state){
        let uri = '/api/get_user_library_time';
        axios.get(uri).then((response) => {
          state.userLibraryTime = response.data;
        });
      },
      //пять самых длинных
      getFiveLongest(state){
        let uri = '/api/get_five_longest';
        axios.get(uri).then((response) => {
          state.fiveLongest = response.data;
        })
      },
    },

    actions: {
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
        context.commit('getSpotifyTracks');
      },
      //получить кол-во альбомов в библиотеке и последние пять
      getSpotifyAlbums(context){
        context.commit('getSpotifyAlbums');
      }, 
      //получить кол-во подписок в библиотеке и случайные пять
      getSpotifyArtists(context){
        context.commit('getSpotifyArtists');
      },
      //посчитать кол-во времени
      getUserLibraryTime(context){
        context.commit('getUserLibraryTime');
      },
      //пять самых длинных
      getFiveLongest(context){
        context.commit('getFiveLongest');
      },
    }
}

export default new Vuex.Store({
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }
});