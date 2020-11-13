<template>
        <div>
        <div class="container bounce_in_anim" v-if="this.$store.state.profilePage.spotifyProfile == -1">
            <div class="row justify-content-center align-items-center">
                 <div class="lds-facebook"><div></div><div></div><div></div></div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center fade-in">
                    <h1 v-if="this.$store.state.profilePage.spotifyProfile != -1" class="fade_in_anim">
                        <b>{{this.$store.state.profilePage.spotifyProfile.spotifyUsername}}</b>
                        <a :href="this.$store.state.profilePage.spotifyProfile.profile_url" target="_blank" style="font-size:10pt;"><i class="fas fa-external-link-alt"></i></a>
                    </h1>
            </div>
            <div v-if="this.$store.state.profilePage.spotifyProfile != -1" class="row justify-content-center">
                    <img :src="this.$store.state.profilePage.spotifyProfile.avatar" alt="Spotify Avatar" class="profile_avatar bounce_in_av_anim">
            </div>
            <div v-if="this.$store.state.profilePage.spotifyProfile != -1" class="row justify-content-center fade-in">
               <h6 v-if="this.$store.state.profilePage.spotifyProfile.subscription == 'premium'" style="margin-bottom:0;">Premium <i class="fas fa-crown"></i></h6>
               <h6 v-else></h6>
            </div>
            <div v-if="this.$store.state.profilePage.spotifyProfile != -1" class="row justify-content-center">
                <div> Подписчики: 1</div>
                <div>&nbsp;|&nbsp;</div>
                <div>
                    <img class="fade_in_anim" :src="this.$store.state.profilePage.spotifyProfile.country">
                </div>
            </div>
            <hr v-if="this.$store.state.profilePage.spotifyProfile != -1" class="fade_in_anim">
        </div>
    </div>
</template>

<script>
export default {
    beforeCreate(){
        this.$store.dispatch('getSpotifyProfile')
    }
}
</script>