<template>
    <div>
        <div class="container bounce_in_anim loader_div" v-if="siteInfo == false">
            <Loader/>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                    <h1 v-if="siteInfo != false" class="fade_in_anim"><b>{{siteInfo.siteTitle}}</b></h1>
            </div>
            <div v-if="siteInfo != false" class="row justify-content-center">
                    <img src="/logo.png" width="90pt" alt="" class="fade_in_anim bounce_in_anim">
            </div>
            <div v-if="siteInfo != false" class="row justify-content-center">
                    <h4 class="fade_in_anim">{{siteInfo.version}}</h4>
            </div>
            <hr v-if="siteInfo != false" class="fade_in_anim">
            <div v-if="siteInfo != false" class="row justify-content-center fade_in_anim">
                <p>
                    {{siteInfo.aboutText}}
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
        computed: {
            siteInfo: function(){
                return this.$store.state.homePage.siteInfo;
            },
        },
        created(){
            this.$store.dispatch('getSiteInfo')
        },
        mounted() {
           console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'About\' component mounted')
        }
    }
</script>