<template>
    <div v-if="cookiesVisible = -1" class="row text-center cookiesCard justify-content-center fadeInAnimSlow" v-bind:class="{invisible: cookiesVisible = false, fadeInAnimSlow: cookiesVisible = true}">
        <h6><b>SpotiFYI</b> использует Cookies. Продолжая вы соглашаетесь, что этот сайт будет иметь доступ к вашим "печенькам" в браузере.</h6>
        <div class="col-12 col-md-2 paddingSides">
            <button class="btn btn-block btn-primary" v-on:click="acceptCookie">Ладно</button>
        </div>
    </div>
</template>
<script>
export default {
    
    data: function(){
        return {
            isInvisible: -1
        }
    },
    
    mounted(){
        this.$store.dispatch('checkCookies'); 
    },
    
    computed: {
        cookiesVisible: function(){
            return this.$store.state.homePage.cookiesVisible;
        }
    },

    methods: {
        acceptCookie: function() {
            this.$store.dispatch('setCookiesVisibleFalse'); 
        },
    }
}
</script>