<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        beforeCreate(){
            this.$store.dispatch('getSpotifyUsername')
            this.$store.dispatch('getHomePageUserTracksCount');
        },
        mounted(){
            console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'HomePage\' component mounted')
        }
    }
</script>