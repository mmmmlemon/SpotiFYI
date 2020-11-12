<template>
    <div class="container">
        <h6>this is a home page</h6>
        <!-- <router-view></router-view> -->
    </div>
</template>

<script>
    export default {

        mounted() {
            console.log('\'HomePage\' component mounted')
        },

        data(){
            return{
                loggedIn: false,
                spotifyUsername: false,
                spotifyUserTracksCount: -1
            }
        },

        created(){
            let uri_username = '/api/get_spotify_username';
            this.axios.get(uri_username).then((response) => {
                this.loggedIn = response.data.loggedIn;
                if(response.data.spotifyUsername != undefined)
                {
                    this.spotifyUsername = response.data.spotifyUsername;
                }
                else
                {
                    this.loggedIn = false;
                }
            })

            let uri = '/api/get_spotify_tracks_count';
            this.axios.get(uri).then((response) => {
                this.spotifyUserTracksCount = response.data;
            });
        }
    }
</script>