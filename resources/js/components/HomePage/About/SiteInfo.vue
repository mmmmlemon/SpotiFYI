<template>
    <div class="fadeInAnim">
        <div v-if="siteInfo == -1" class="fadeInAnim">
            <Loader />
        </div>
        <div v-else-if="siteInfo != -1 && siteInfo != false" class="fadeInAnim">
            <p v-html="siteInfo" class="fadeInAnim">
            </p>
        </div>
        <div v-else class="fadeInAnim">
            <Error type="small" errorMessage="Не удалось загрузить информацию о сайте"/>
        </div>
    </div>
    
</template>
<script>
export default {
    mounted(){

        //смена текущего таба
        this.$store.dispatch('setCurrentTab','about');

        //получить информацию о сайте
        this.$store.dispatch('getAbout');

    },

    computed: {
        //информация о сайте
        siteInfo: function(){
            return this.$store.state.homePage.about;
        },
    },

}
</script>