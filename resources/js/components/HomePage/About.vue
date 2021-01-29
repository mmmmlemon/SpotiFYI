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
                <div class="greyCard col-12 padding_10">
                    <!-- название сайта -->
                    <div class="row justify-content-center fadeInAnimSlow">
                        <h1 v-if="siteInfo != false"><b>{{siteInfo.siteTitle}}</b></h1>
                    </div>
                    <!-- логотип -->
                    <div v-if="siteInfo != false" class="row justify-content-center bounceInAnim">
                        <img :src="siteLogoUrl" width="90pt" alt="Site logo" >
                    </div>
                    <!-- версия -->
                    <div v-if="siteInfo != false" class="row justify-content-center fadeInAnimSlow">
                        <h5>{{siteInfo.version}}</h5>
                    </div>

                    <hr v-if="siteInfo != false" class="fadeInAnim">

                    <!-- powered by -->
                    <div v-if="siteInfo != false" class="row justify-content-center text-center fadeInAnimSlow">
                        <div class="col-12">
                            <b>Powered by</b>
                        </div>
                        <p v-html="siteInfo.poweredBy" class="text-center marginNone paddingNone p_fix">  
                        </p>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-4 paddingSides">
                            <router-link to="/about">
                                <button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'about'}" type="button">
                                    О проекте
                                </button>
                            </router-link>
                        </div>
                        <div class="col-md-4 paddingSides">
                            <router-link to="/about/faq">
                                <button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'faq'}" type="button">
                                    FAQ
                                </button>
                            </router-link>
                        </div>
                        <div class="col-md-4 paddingSides">
                            <router-link to="/about/contacts">
                                <button class="btn btn-block" v-bind:class="{ 'btn-primary': currentTab === 'contacts'}" type="button">
                                   Контакты
                                </button>
                            </router-link>
                        </div>

                    </div>
 
                </div>
                <!-- информация о сайте -->
                <div class="col-12 col-md-8 paddingSides fadeInAnim">
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
        mounted() {
            //получить логотип сайта
            this.$store.dispatch('getSiteLogoUrl');

            //получить информацию о сайте
            this.$store.dispatch('getSiteInfo');

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
            }
        },


    }
</script>