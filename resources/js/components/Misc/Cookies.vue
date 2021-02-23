//Cookies
<template>
    <div v-if="cookiesVisible != -1" style="z-index: 111;" class="row text-center cookiesCard justify-content-center fadeInAnimSlow" v-bind:class="{invisible: cookiesVisible === false}">
        <div class="col-12">
            <h6><b>SpotiFYI</b> использует Cookies. Продолжая вы соглашаетесь, что этот сайт будет иметь доступ к вашим "печенькам" в браузере.</h6>
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-block btn-primary" v-on:click="acceptCookie">
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