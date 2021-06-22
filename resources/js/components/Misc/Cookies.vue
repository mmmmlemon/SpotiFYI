//Cookies
<template>
    <div v-if="cookiesVisible != -1" style="z-index: 1;" class="row text-center cookiesCard justify-content-center fadeInAnimSlow" v-bind:class="{invisible: cookiesVisible === false}">
        <div class="col-12">
            <h6>Этот сайт использует Cookies для авторизации. Продолжая вы соглашаетесь, что этот сайт будет иметь доступ к вашим "печенькам" в браузере. <br><br>
            Что такое Cookies можно почитать, например, <a class="borderUnderline" target="_blank" href="https://ru.wikipedia.org/wiki/Cookie">вот тут</a>.</h6>
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-block btn-primary-n" v-on:click="acceptCookie">
                Ладно
                <i class="fas fa-cookie-bite"></i>
            </button>
        </div>
    </div>
</template>
<script>
export default {

    mounted(){
        this.$store.dispatch('checkCookies'); 
    },
    
    computed: {
        cookiesVisible: {
            get: function(){
                return this.$store.state.homePage.cookiesVisible;
            },
            set: function(newValue){
               this.$store.dispatch('setCookiesVisibleFalse'); 
            }
        }
    },

    methods: {
        acceptCookie: function() {
            this.cookiesVisible = false;
        },
    }
}
</script>