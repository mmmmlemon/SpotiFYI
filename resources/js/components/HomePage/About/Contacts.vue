//Contacts
<template>
    <div class="fadeInAnim">
        <!-- лоадер -->
        <div v-if="contacts == -1" class="fadeInAnim">
            <Loader />
        </div>
        <!-- текст -->
        <div v-else-if="contacts != -1 && contacts != false" class="fadeInAnim">
            <p v-html="contacts" class="fadeInAnim text-center">
            </p>
        </div>
        <!-- ошибка -->
        <div v-else class="fadeInAnim">
            <Error type="small" errorMessage="Не удалось загрузить контакты"/>
        </div>
    </div>
    
</template>
<script>
export default {
    mounted(){
        //смена текущего таба
        this.$store.dispatch('setCurrentTab','contacts');

    },

    computed: {
        //информация о сайте
        contacts: function(){
            return this.$store.state.homePage.contacts;
        },
    },

}
</script>