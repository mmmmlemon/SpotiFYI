<template>
        <div class="col-md-5 margin_sides padding_10">
            <div v-if="items == -1">
                <Loader />
                <h6 class="font_10pt text-center blinking_anim">{{loaderMessage}}</h6>
            </div>
            <div v-else-if="items == false">
                <Error type="small" errorMessage="Не удалось загрузить треки"/>
            </div>
            <div v-else-if="items != -1" class="col-md-12 padding_10 grey_card margin_sides fade_in_anim"> 
                <div class="top10_image_card" :style="{backgroundImage: `url('${items['backgroundImage']}')`}">
                </div>
                <div>
                    <h4 class="border_underline text-center"><b>{{cardTitle}}</b></h4>
                    <p class="font_10pt text-center" v-if="cardDesc != undefined">{{cardDesc}}</p>
                    <div class="row fade_in_anim">
                        <!-- список для треков -->
                        <div v-if="items != undefined && listType == 'tracks'">
                            <div class="row fade_in_anim" v-for="item in items['tracks']" :key="item.id">
                                <div class="col-2">
                                    <div class="number_card">
                                        <b>{{item.count}}</b>
                                    </div>
                                    <a :href="item.url" target="_blank">
                                        <img :src="item.cover" class="rounded-circle album_icon_big">
                                    </a>
                                </div>
                                <div class="col-10">
                                    <p class="font_13pt font_white margin_none"><a :href="item.url" target="_blank"><b>{{item.track_name}}</b></a></p>
                                    <p class="font_10pt margin_none font_white" style="margin-bottom:7px;">
                                        <a :href="item.album_url" target="_blank">
                                            <b v-if="item.duration">{{item.duration}}  </b>
                                            <b class="unbold">{{item.album}} - {{item.album_year}}</b>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- список для артистов -->
                        <div v-else-if="items != undefined && listType == 'artists'">
                            <div class="row fade_in_anim" v-for="item in items['artists']" :key="item.id">
                                <div class="col-2">
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
                                                <p class="unbold zero_opacity font_8pt">
                                                    нЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕт, ТЫ НЕ МОЖЕШЬ ПРОСТО ТАК ВОТКНУТЬ НЕВИДИМЫЙ ТЕКСТ
                                                </p>
                                            </b>
                                            <b v-else class="unbold zero_opacity">
                                                нЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕЕт, ТЫ НЕ МОЖЕШЬ ПРОСТО ТАК ВОТКНУТЬ НЕВИДИМЫЙ ТЕКСТ

                                                хахаа opacity: 0 goes brrr
                                            </b>
                                    </p>
                                </div>
                            </div>
                        </div>
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
        loaderMessage: { default: '' },
        cardTitle: { default: 'Топ 10' },
        cardDesc: { default : undefined },
        items: { default: -1 },
        listType: { default: "tracks" }
    },
}
</script>