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
        yearsAndDecadesMonth: -1, //года и десятилетия за месяц

        top10TracksAllTime: -1, //топ 10 треков за все время
        top10TracksMonth: -1, // топ 10 треков за месяц
        top10ArtistsAllTime: -1, //топ 10 исполнителей за все время
        top10ArtistsMonth: -1, //топ 10 исполнителей за месяц
        top10TracksLong: -1, //топ 10 длинных треков
        top10TracksShort: -1, //топ 10 коротких треков
        top10PopularTracks: -1, //топ 10 популярных треков
        top10UnpopularTracks: -1, //топ 10 непопулярных треков
        top10ArtistsByTracks: -1, //топ 10 исполнителей по кол-ву треков
        top10ArtistsByTime: -1, //топ 10 исполнителей по времени треков
        mostListenedTrack: -1, //самый прослушиваемый трек за все время
        mostListenedTrackMonth: -1, //самый прослушиваемый трек за месяц
        mostPopularTrack: -1, //самый популярный трек в библиотеке
        leastPopularTrack: -1, //самый непопулярный трек в библиотеке
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
          //получить данные о профиле Spotify
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

          //базовая статистика
          //получить треки и последние пять треков
          getSpotifyTracks(state){
            axios.get('/api/get_spotify_tracks').then((response) => {
              state.spotifyTracks = response.data;
            });
          },
          //получить альбомы и последние пять альбомов
          getSpotifyAlbums(state){
            axios.get('/api/get_spotify_albums').then((response) => {
              state.spotifyAlbums = response.data;
            });
          },
          //получить подписки на артистов и случайные пять подписок
          getSpotifyArtists(state){
            axios.get('/api/get_spotify_artists').then((response) => {
              state.spotifyArtists = response.data;
            });
          },
          //поcчитать кол-во времени
          getUserLibraryTime(state){
            axios.get('/api/get_user_library_time').then((response) => {
              state.userLibraryTime = response.data;
            });
          },
          //пять самых длинных и коротких треков
          getFiveLongestAndShortestTracks(state){
            axios.get('/api/get_five_tracks').then((response) => {
              state.fiveTracks = response.data;
            });
          },
          //получить среднюю длину трека
          getAverageLengthOfTrack(state){
            axios.get('/api/get_average_track_length').then((response) => {
              state.tracksMode = response.data;
            });
          },
          //получить любимые жанры
          getFavoriteGenres(state){
            axios.get('/api/get_favorite_genres').then((response) => {
              state.favoriteGenres = response.data;
            });
          },
          //получить кол-во артистов
          getUniqueArtists(state){
            axios.get('/api/get_unique_artists').then((response) => {
              state.uniqueArtists = response.data;
            });
          },
          //получить информацию по годам и десятилетиям
          getYearsAndDecades(state){
            axios.get('/api/get_years_and_decades/alltime').then((response) => {
              state.yearsAndDecades = response.data;
            });
          },
          //получить информацию по годам и десятилетиям за месяц
          getYearsAndDecadesMonth(state){
            axios.get('/api/get_years_and_decades/month').then((response) => {
              state.yearsAndDecadesMonth = response.data;
            });
          },


          //топ 10
          //получить топ 10 треков за всё время
          getTop10TracksAllTime(state){
            axios.get('/api/get_top10_tracks/alltime').then((response) => {
              state.top10TracksAllTime = response.data;
            });
          },
          //получить топ 10 треков за месяц
          getTop10TracksMonth(state){
            axios.get('/api/get_top10_tracks/month').then((response) => {
              state.top10TracksMonth = response.data;
            });
          },
          //получить топ 10 самых длинных треков
          getTop10TracksLong(state){
            axios.get('/api/get_top10_tracks_by_length/long').then((response) => {
              state.top10TracksLong = response.data;
            });
          },
          //получить топ 10 самых коротких треков
          getTop10TracksShort(state){
              axios.get('/api/get_top10_tracks_by_length/short').then((response) => {
                state.top10TracksShort = response.data;
              });
          },
          //получить топ 10 самых популярных треков
          getTop10PopularTracks(state){
            axios.get('/api/get_top10_tracks_by_popularity/popular').then((response) => {
              state.top10PopularTracks = response.data;
              });
          },
          //получить топ 10 самых популярных треков
          getTop10UnpopularTracks(state){
            axios.get('/api/get_top10_tracks_by_popularity/unpopular').then((response) => {
              state.top10UnpopularTracks = response.data;
              });
          },
          //получить топ 10 артистов за всё время
          getTop10ArtistsAllTime(state){
            axios.get('/api/get_top10_artists/alltime').then((response) => {
              state.top10ArtistsAllTime = response.data;
            });
          },
          //получить топ 10 артистов за месяц
          getTop10ArtistsMonth(state){
            axios.get('/api/get_top10_artists/month').then((response) => {
              state.top10ArtistsMonth = response.data;
            });
          },
          //получить топ 10 артистов по кол-ву треков
          getTop10ArtistsByTracks(state){
            axios.get('/api/get_top10_artists_by_tracks').then((response) => {
              state.top10ArtistsByTracks = response.data;
            })
          },
          //получить топ 10 артистов по кол-ву треков
          getTop10ArtistsByTime(state){
            axios.get('/api/get_top10_artists_by_time').then((response) => {
              state.top10ArtistsByTime = response.data;
            })
          },

          //ачивки
          //самый прослушиваемый трек
          getMostListenedTrack(state){
            axios.get('/api/get_most_listened_track/alltime').then((response) => {
              state.mostListenedTrack = response.data;
            })
          },
          //самый прослушиваемый трек за месяц
          getMostListenedTrackMonth(state){
            axios.get('/api/get_most_listened_track/month').then((response) => {
              state.mostListenedTrackMonth = response.data;
            })
          },
          //самый популярный трек
          getMostPopularTrack(state){
            axios.get('/api/get_track_by_popularity/popular').then((response) => {
              state.mostPopularTrack = response.data;
            })
          },
          //самый непопулярный трек
          getLeastPopularTrack(state){
            axios.get('/api/get_track_by_popularity/unpopular').then((response) => {
              state.leastPopularTrack = response.data;
            })
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
      //пять самых длинных и коротких треков
      getFiveLongestAndShortestTracks(context){
        context.commit('getFiveLongestAndShortestTracks');
      },
      //средняя длина трека
      getAverageLengthOfTrack(context){
        context.commit('getAverageLengthOfTrack');
      },
      //получить любимые жанры
      getFavoriteGenres(context){
        context.commit('getFavoriteGenres');
      },
      //кол-во исполнителей
      getUniqueArtists(context){
        context.commit('getUniqueArtists');
      },
      //посчитать года и десятилетия
      getYearsAndDecades(context){
        context.commit('getYearsAndDecades');
      },
      //посчитать года и десятилетия за месяц
      getYearsAndDecadesMonth(context){
        context.commit('getYearsAndDecadesMonth');
      },

      //топ10
      //топ10 треков за все время
      getTop10TracksAllTime(context){
        context.commit('getTop10TracksAllTime');
      },
      //топ10 треков за все время
      getTop10TracksMonth(context){
        context.commit('getTop10TracksMonth');
      },
      //топ 10 длинных треков
      getTop10TracksLong(context){
        context.commit('getTop10TracksLong');
      },
      //топ 10 коротких треков
      getTop10TracksShort(context){
        context.commit('getTop10TracksShort');
      },
      //топ 10 коротких треков
      getTop10PopularTracks(context){
        context.commit('getTop10PopularTracks');
      },
      //топ 10 коротких треков
      getTop10UnpopularTracks(context){
        context.commit('getTop10UnpopularTracks');
      },
      //топ 10 исполнителей за все время
      getTop10ArtistsAllTime(context){
        context.commit('getTop10ArtistsAllTime');
      },
      //топ10 исполнителей за все время
      getTop10ArtistsMonth(context){
        context.commit('getTop10ArtistsMonth');
      },
      //топ 10 исполнителей по кол-ву треков
      getTop10ArtistsByTracks(context){
        context.commit('getTop10ArtistsByTracks');
      },
      //топ 10 исполнителей по времени треков
      getTop10ArtistsByTime(context){
        context.commit('getTop10ArtistsByTime');
      },

      //ачивки
      //самый прослушиваемый трек за все время
      getMostListenedTrack(context){
        context.commit('getMostListenedTrack');
      },
      //самый прослушиваемый трек
      getMostListenedTrackMonth(context){
        context.commit('getMostListenedTrackMonth');
      },
      //самый популярный трек
      getMostPopularTrack(context){
        context.commit('getMostPopularTrack');
      },
      //самый популярный трек
      getLeastPopularTrack(context){
        context.commit('getLeastPopularTrack');
      }

  }
}

export default new Vuex.Store({
  modules: {
      homePage: HomePageStates,
      profilePage: ProfilePageStates
  }
});