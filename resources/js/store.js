import Vuex from 'vuex'
import Vue from 'vue'

import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(Vuex)
Vue.use(VueAxios, axios);

const HomePageStates = {
    state: {
        loggedIn: false,
        spotifyUsername: false,
        spotifyUserTracksCount: -1
      },

      getters: {
        //геттеры
      },
      
      mutations: {
        //получить имя пользователя из API
        getSpotifyUsername(state){
            let uri_username = '/api/get_spotify_username';
            axios.get(uri_username).then((response) => {
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
        }
      }
}

const ProfilePageStates = {
    state: {
        dummy: false
      },
}

export default new Vuex.Store({
 
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }

});