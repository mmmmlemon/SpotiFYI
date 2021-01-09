//HomePage
<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        mounted(){

            //получить юзернейм пользователя
            this.$store.dispatch('getSpotifyUsername')

            //получить кол-во треков в библиотеке для сообщения на главной странице
            this.$store.dispatch('getHomePageUserTracksCount');
        }
    }
</script>