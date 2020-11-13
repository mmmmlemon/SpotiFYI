<template>
    <div>
        <div class="container bounce_in_anim loader_div" v-if="this.$store.state.homePage.siteInfo == false">
            <Loader/>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                    <h1 v-if="this.$store.state.homePage.siteInfo != false" class="fade_in_anim"><b>{{this.$store.state.homePage.siteInfo.siteTitle}}</b></h1>
            </div>
            <div v-if="this.$store.state.homePage.siteInfo != false" class="row justify-content-center">
                    <img src="/logo.png" width="90pt" alt="" class="fade_in_anim bounce_in_anim">
            </div>
            <div v-if="this.$store.state.homePage.siteInfo != false" class="row justify-content-center">
                    <h4 class="fade_in_anim">{{this.$store.state.homePage.siteInfo.version}}</h4>
            </div>
            <hr v-if="this.$store.state.homePage.siteInfo != false" class="fade_in_anim">
            <div v-if="this.$store.state.homePage.siteInfo != false" class="row justify-content-center fade_in_anim">
                <p>
                    {{this.$store.state.homePage.siteInfo.aboutText}}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                site_name: false,
            }
        },
        created(){
            this.$store.dispatch('getSiteInfo')
            this.site_name = this.$store.state.homePage.siteTitle;
        },
        mounted() {
           console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'About\' component mounted')
        }
    }
</script>