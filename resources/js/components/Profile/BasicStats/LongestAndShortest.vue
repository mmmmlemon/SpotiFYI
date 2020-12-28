<template>
    <div class="row justify-content-center">
        <!-- пять самых длинных -->
        <div v-if="fiveLongest === -1" class="col-md-6 padding_10">
            <Loader />
        </div>
        <div v-else-if="fiveLongest === false" class="col-md-6 padding_10">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div class="col-md-5 padding_10 grey_card margin_sides" v-else-if="fiveLongest != -1">
            <h4 class="border_underline text-center"><b>Пять самых длинных песен</b></h4>
            <div class="row fade_in_anim" v-for="item in fiveLongest" :key="item.id">
                <div class="col-2">
                    <div class="number_card">
                        <b>{{item.count}}</b>
                    </div>
                    <a :href="item.url" target="_blank">
                        <img :src="item.cover" class="rounded-circle album_icon_big">
                    </a>
                </div>
                <div class="col-10">
                    <p class="font_13pt font_white margin_none"><a :href="item.url" target="_blank"><b>{{item.name}}</b></a></p>
                    <p class="font_13pt margin_none font_white"><b>{{item.duration}}</b></p>
                </div>
            </div>
        </div>
        <div v-else>
            <Error type="small"/>
        </div>
        
        <!-- пять самых коротких -->
        <div v-if="fiveShortest === -1" class="col-md-6 padding_10">
            <Loader />
        </div>
        <div v-else-if="fiveShortest === false && fiveLongest != -1" class="col-md-6 padding_10">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div class="col-md-5 padding_10 grey_card margin_sides" v-else-if="fiveShortest != -1">
            <h4 class="border_underline text-center"><b>Пять самых коротких песен</b></h4>
            <div class="row fade_in_anim" v-for="item in fiveShortest" :key="item.id">
                <div class="col-2">
                    <div class="number_card">
                        <b>{{item.count}}</b>
                    </div>
                    <a :href="item.url" target="_blank">
                        <img :src="item.cover" class="rounded-circle album_icon_big">
                    </a>
                </div>
                <div class="col-10">
                    <p class="font_13pt font_white margin_none"><a :href="item.url" target="_blank"><b>{{item.name}}</b></a></p>
                    <p class="font_13pt margin_none font_white"><b>{{item.duration}}</b></p>
                </div>
            </div>
        </div>
        <div v-else>
            <Error type="small"/>
        </div>
        <!-- средняя длина трека -->
        <div v-if="tracksMode === -1" class="col-md-6 padding_10">
            <Loader />
        </div>
        <div v-else-if="tracksMode === false" class="col-md-6 padding_10">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div v-else-if="tracksMode != -1 && fiveShortest != -1" class="col-md-12 text-center fade_in_anim margin_vertical padding_10">
            <hr>
            <p class="padding_10">
                Средняя продолжительность трека в твоей библиотеке - 
                <b class="border_underline font_25pt">{{tracksMode}}</b>
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