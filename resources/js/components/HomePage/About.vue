//About
<template>
    <div>
        <!-- лоадер -->
        <div class="container bounceInAnim marginTopBig" v-if="siteInfo == -1">
            <Loader/>
        </div>
        <!-- если информация загрузилась -->
        <div class="container fadeInAnim" v-if="siteInfo != false && siteInfo != -1">
            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- название сайта -->
                    <div class="row justify-content-center fadeInAnim">
                        <h2 class="text-center siteTitleHome" v-if="siteInfo != false">{{siteInfo.siteTitle}} <b class="betaAbout">{{siteInfo.version}}</b></h2>
                    </div>
                    <!-- логотип -->
                    <div v-if="siteInfo != false" class="row justify-content-center fadeInAnim">
                        <Logo :animation="false"/>
                    </div>
                    <!-- версия -->
                    <div v-if="siteInfo != false" class="row justify-content-center fadeInAnim">
                        <h5></h5>
                    </div>

                    <!-- powered by -->
                    <div v-if="siteInfo != false" class="row justify-content-center text-center borderUnderline fadeInAnim" style="margin-bottom: 1rem;">
                        <div class="col-12" v-if="siteInfo.poweredBy != -1 && siteInfo.poweredBy != false">
                            <b>Powered by</b>
                        </div>
                        <p v-html="siteInfo.poweredBy" class="text-center p_fix">  
                        </p>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-4 paddingSides">
                            <router-link to="/about">
                                <button class="btn btn-block" v-bind:class="{ 'btn-primary-n': currentTab === 'about'}" type="button">
                                    О проекте
                                </button>
                            </router-link>
                        </div>
                        <div class="col-md-4 paddingSides">
                            <router-link to="/about/faq">
                                <button class="btn btn-block" v-bind:class="{ 'btn-primary-n': currentTab === 'faq'}" type="button">
                                    FAQ
                                </button>
                            </router-link>
                        </div>
                        <div class="col-md-4 paddingSides">
                            <router-link to="/about/contacts">
                                <button class="btn btn-block" v-bind:class="{ 'btn-primary-n': currentTab === 'contacts'}" type="button">
                                   Контакты
                                </button>
                            </router-link>
                        </div>

                    </div>
 
                </div>
                <!-- информация о сайте -->
                <div class="col-12 col-md-8 fadeInAnim">
                    <router-view></router-view>
                </div>
            </div>
        </div>
        <!-- ошибка -->
        <div v-else-if="siteInfo == false">
            <Error errorMessage="Не удалось загрузить информацию о сайте."/>
        </div>
    </div>
</template>

<script>
    export default {

        created(){
            //получить информацию о сайте
            if(this.contacts === -1)
            { this.$store.dispatch('getContacts'); }

            if(this.faq === -1)
            { this.$store.dispatch('getFAQ'); }

            if(this.about === -1)
            { this.$store.dispatch('getAbout'); }
            
        },

        computed: {
             //текущая вкладка
            currentTab: function(){
                return this.$store.state.profilePage.currentTab;
            },
            //информация о сайте
            siteInfo: function(){
                return this.$store.state.homePage.siteInfo;
            },
            //ссылка на логотип сайта
            siteLogoUrl: function(){
                return this.$store.state.homePage.siteLogoUrl;
            },
            //информация о сайте
            contacts: function(){
                return this.$store.state.homePage.contacts;
            },
            //информация о сайте
            faq: function(){
                return this.$store.state.homePage.faq;
            },
            //информация о сайте
            about: function(){
                return this.$store.state.homePage.about;
            },
        },


    }
</script>