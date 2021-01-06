<template>
        <div class="col-md-5 margin_sides padding_10">
            <div v-if="items == -1">
                <Loader />
            </div>
            <div v-else-if="items == false">
                <Error type="small" errorMessage="Не удалось загрузить треки"/>
            </div>
            <div v-else-if="items != -1 || items != false" class="col-md-12 padding_10 grey_card fade_in_anim"> 
                <div class="top10_image_card" :style="{backgroundImage: `url('${items['backgroundImage']}')`}">
                </div>
                <div>
                    <h4 class="border_underline text-center"><b>{{cardTitle}}</b></h4>
                    <p class="font_10pt text-center" v-if="cardDesc != undefined">{{cardDesc}}</p>
                    <div class="row fade_in_anim">
                        <!-- список для треков -->
                        <div v-if="items != undefined">
                            <div class="row fade_in_anim" v-for="item in items['items']" :key="item.id">
                                <div class="col-2">
                                    <div class="number_card">
                                        <b>{{item.count}}</b>
                                    </div>
                                    <a :href="item.url" target="_blank">
                                        <img :src="item.image" class="rounded-circle album_icon_top10">
                                    </a>
                                </div>
                                <div class="col-10">
                                    <p class="font_13pt font_white margin_none"><a :href="item.url" target="_blank"><b>{{item.name}}</b></a></p>
                                    <p class="font_10pt margin_none font_white" style="margin-bottom:7px;" v-if="item.album">
                                        <a :href="item.album_url" target="_blank">
                                            <b v-if="item.duration">{{item.duration}}  </b>
                                            <b class="unbold">{{item.album}} - {{item.album_year}}</b>
                                        </a>
                                    </p>
                                    <p class="font_10pt margin_none font_white" style="margin-bottom:7px;" v-if="item.genres">
                                        <b class="unbold">{{item.genres}}</b>
                                    </p>
                                    <p class="font_10pt margin_none font_white" style="margin-bottom:7px;" v-if="item.track_count">
                                        <b class="unbold">{{item.track_count}}</b>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- список для артистов
                        <div v-else-if="items != undefined && listType == 'artists'">
                            <div class="row fade_in_anim" v-for="item in items['artists']" :key="item.id">
                                <div class="col-2 margin_vertical">
                                    <div class="number_card">
                                        <b class="">{{item.count}}</b>
                                    </div>
                                    <a :href="item.url" target="_blank">
                                        <img :src="item.photo" class="rounded-circle album_icon_big">
                                    </a>
                                </div>
                                <div class="col-10">
                                    <p class="font_13pt font_white margin_none"><a :href="item.url" target="_blank"><b>{{item.artist_name}}</b></a></p>
                                    <p class="font_10pt margin_none font_white" style="margin-bottom:7px;">
                                            <b v-if="item.track_count" class="unbold">
                                                {{item.track_count}}
                                                    </b>
                                            <b v-else class="unbold zero_opacity">
                                                жанры жанры жанры жанры жанры
                                            </b>
                                    </p>
                                </div>
                            </div>
                        </div> -->
                        <div v-else>
                            <Error type="x-small" errorMessage="Нечего показывать. Параметр items пустой."/>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <Error type="x-small" errorMessage="Неизвестная ошибка"/>
            </div>
        </div>
</template>
<script>
export default {
    props: {
        cardTitle: { default: 'Топ 10' },
        cardDesc: { default : undefined },
        items: { default: -1 },
        listType: { default: "tracks" }
    },
}
</script>