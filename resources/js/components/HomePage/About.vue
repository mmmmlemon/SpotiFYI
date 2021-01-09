//About
<template>
    <div>
        <!-- лоадер -->
        <div class="container bounce_in_anim loader_div" v-if="siteInfo == -1">
            <Loader/>
        </div>
        <!-- если информация загрузилась -->
        <div class="container fade_in_anim" v-if="siteInfo != false && siteInfo != -1">
            <div class="row justify-content-center">
                <div class="grey_card col-12 padding_10">
                    <div class="row justify-content-center">
                        <h1 v-if="siteInfo != false" class="fade_in_anim"><b>{{siteInfo.siteTitle}}</b></h1>
                    </div>
                    <div v-if="siteInfo != false" class="row justify-content-center">
                            <img src="/logo.png" width="90pt" alt="" class="fade_in_anim bounce_in_anim">
                    </div>
                    <div v-if="siteInfo != false" class="row justify-content-center">
                            <h5 class="fade_in_anim">{{siteInfo.version}}</h5>
                    </div>
                    <hr v-if="siteInfo != false" class="fade_in_anim">
                    <div v-if="siteInfo != false" class="row justify-content-center fade_in_anim text-center">
                        <p>
                            {{siteInfo.poweredBy}}
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-8 padding_10">
                    <p>Много текста про сайт. Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores natus veniam voluptas cum provident voluptate possimus est itaque, laudantium eos quae tempora nobis optio eveniet, pariatur facilis dolor rem architecto.</p>
                    <hr>
                    <p>Еще много текста про сайт. Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore ducimus iure vitae, dolores soluta corrupti sit accusamus natus deleniti maiores reprehenderit numquam? Harum enim impedit neque natus sed molestias perferendis.Laudantium commodi dolorem accusamus eligendi aliquid adipisci illum possimus iusto vitae, ipsa libero. Saepe, nisi dolor facere eius tempore facilis illum voluptatibus labore, explicabo expedita et magnam cumque aut debitis?</p>
                </div>
            </div>
        </div>
        <div v-else-if="siteInfo == false">
            <Error errorMessage="Не удалось загрузить информацию о сайте."/>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {
            //получить информацию о сайте
            this.$store.dispatch('getSiteInfo');
        },
        computed: {
            //информация о сайте
            siteInfo: function(){
                return this.$store.state.homePage.siteInfo;
            },
        },
    }
</script>