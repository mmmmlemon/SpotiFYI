//FAQ
<template>
    <div class="fadeInAnim">
        <!-- лоадер -->
        <div v-if="faq == -1" class="fadeInAnim">
            <Loader />
        </div>
        <!-- текст FAQ -->
        <div v-else-if="faq != -1 && faq != false" class="fadeInAnim">
            <p v-html="faq" class="fadeInAnim text-center">
            </p>
        </div>
        <!-- ошибка -->
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

    },

    computed: {
        //информация о сайте
        faq: function(){
            return this.$store.state.homePage.faq;
        },
    },

}
</script>