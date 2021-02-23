//LongestAndShortest
<template>
    <div class="row justify-content-center fadeInAnim">
        <!-- пять самых длинных -->
        <div v-if="fiveLongest === -1" class="col-11 col-md-5 paddingSides fadeInAnim">
            <Loader />
        </div>
        <div v-else-if="fiveLongest === false" class="col-11 col-md-5 paddingSides">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div class="col-11 col-lg-5 paddingSides marginSides greyCard fadeInAnim" v-else-if="fiveLongest != -1">
            <h4 class="text-center borderUnderline"><b>Пять самых длинных песен</b></h4>
            <div class="row marginTopSmall" v-for="item in fiveLongest" :key="item.id">
                <div class="col-2">
                    <div class="numberCircle">
                        <b>{{item.count}}</b>
                    </div>
                    <a :href="item.url" target="_blank">
                        <img :src="item.cover" class="rounded-circle albumIconBig">
                    </a>
                </div>
                <div class="col-10">
                    <p class="font13pt whiteColor marginNone">
                        <a :href="item.url" target="_blank">
                            <b>{{item.name}}</b>
                        </a>
                    </p>
                    <p class="font13pt greyColor marginNone">
                        <b>{{item.duration}}</b>
                    </p>
                </div>
            </div>
        </div>
        <div v-else>
            <Error type="small"/>
        </div>
        
        <!-- пять самых коротких -->
        <div v-if="fiveShortest === -1" class="col-11 col-md-5 paddingSides fadeInAnim">
            <Loader />
        </div>
        <div v-else-if="fiveShortest === false && fiveLongest != -1" class="col-12 col-md-5 paddingSides">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div class="col-11 col-lg-5 paddingSides marginSides greyCard fadeInAnim" v-else-if="fiveShortest != -1">
            <h4 class="text-center borderUnderline"><b>Пять самых коротких песен</b></h4>
            <div class="row marginTopSmall" v-for="item in fiveShortest" :key="item.id">
                <div class="col-2">
                    <div class="numberCircle">
                        <b>{{item.count}}</b>
                    </div>
                    <a :href="item.url" target="_blank">
                        <img :src="item.cover" class="rounded-circle albumIconBig">
                    </a>
                </div>
                <div class="col-10">
                    <p class="font13pt whiteColor marginNone">
                        <a :href="item.url" target="_blank">
                            <b>{{item.name}}</b>
                        </a>
                    </p>
                    <p class="font13pt greyColor marginNone">
                        <b>{{item.duration}}</b>
                    </p>
                </div>
            </div>
        </div>
        <div v-else>
            <Error type="small"/>
        </div>
        <!-- средняя длина трека -->
        <div v-if="tracksMode === -1" class="col-11 col-md-6 paddingSides">
            <Loader />
        </div>
        <div v-else-if="tracksMode === false" class="col-11 col-md-6 paddingSides">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div v-else-if="tracksMode != -1 && fiveShortest != -1" class="col-12 text-center fadeInAnim marginVertical paddingSides">
            <hr>
            <p class="paddingSides">
                Средняя продолжительность трека в твоей библиотеке - 
                <b class="font25pt borderUnderline">{{tracksMode}}</b>
            </p>
            <hr>
        </div>
        <div v-else>
             <Error type="small"/>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        fiveLongest: { default: -1 },
        fiveShortest: {default: -1 },
        tracksMode: {default: -1 },
    },
    computed:{
        //фон профиля
        profileBackgroundUrl: function() {
            return this.$store.state.profilePage.profileBackgroundUrl;
        },
    }
}
</script>