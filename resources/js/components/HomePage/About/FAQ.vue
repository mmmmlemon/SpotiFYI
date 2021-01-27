<template>
    <div class="fadeInAnim">
        <div v-if="faq == -1" class="fadeInAnim">
            <Loader />
        </div>
        <div v-else-if="faq != -1 && faq != false" class="fadeInAnim">
            <p v-html="faq" class="fadeInAnim">
            </p>
        </div>
        <div v-else class="fadeInAnim">
            <Error type="small" errorMessage="Не удалось загрузить FAQ"/>
        </div>
    </div>
    
</template>
<script>
export default {
    mounted(){

        //смена текущего таба
        this.$store.dispatch('setCurrentTab','faq');

        //получить информацию о сайте
        this.$store.dispatch('getFAQ');
    },

    computed: {
        //информация о сайте
        faq: function(){
            return this.$store.state.homePage.faq;
        },
    },

}
</script>