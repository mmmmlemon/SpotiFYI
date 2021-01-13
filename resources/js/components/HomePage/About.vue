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
                        <p><b>Powered by</b><br>{{siteInfo.poweredBy}}</p>
                    </div>
                </div>
                <!-- информация о сайте -->
                <div class="col-12 col-md-8 paddingSides fadeInAnim">
                    <p>Много текста про сайт. Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores natus veniam voluptas cum provident voluptate possimus est itaque, laudantium eos quae tempora nobis optio eveniet, pariatur facilis dolor rem architecto.</p>
                    <hr>
                    <p>Еще много текста про сайт. Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore ducimus iure vitae, dolores soluta corrupti sit accusamus natus deleniti maiores reprehenderit numquam? Harum enim impedit neque natus sed molestias perferendis.Laudantium commodi dolorem accusamus eligendi aliquid adipisci illum possimus iusto vitae, ipsa libero. Saepe, nisi dolor facere eius tempore facilis illum voluptatibus labore, explicabo expedita et magnam cumque aut debitis?</p>
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