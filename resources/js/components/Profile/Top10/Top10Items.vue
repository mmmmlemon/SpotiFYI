//Top10Items
<template>
        <div class="col-12 col-md-5 marginSides paddingSides">
            <div v-if="items == -1">
                <Loader />
            </div>
            <div v-else-if="items == false">
                <Error type="small" errorMessage="Не удалось загрузить треки"/>
            </div>
            <div v-else-if="items == 'noTracks'">
                <Info type="small" infoMessage="Пока что мало данных для Топ-10"/>
            </div>
            <div v-else-if="items != -1 || items != false" class="col-12 fadeInAnim paddingSides greyCard"> 
                 <BackgroundImage bgStyle="top10ImageCard" :backgroundImageUrl="items['backgroundImage']"/>
                 <div>
                    <h4 class="text-center borderUnderline"><b>{{cardTitle}}</b></h4>
                    <p class="text-center font10pt" v-if="cardDesc != undefined">{{cardDesc}}</p>
                    <div class="row fadeInAnim">
                        <!-- список для треков -->
                        <div v-if="items != undefined">
                            <div class="row fadeInAnim" v-for="item in items['items']" :key="item.id">
                                <div class="col-2">
                                    <div class="numberCircle">
                                        <b>{{item.count}}</b>
                                    </div>
                                    <a :href="item.url" target="_blank">
                                        <img :src="item.image" class="rounded-circle albumIconTop10">
                                    </a>
                                </div>
                                <div class="col-10">
                                    <p class="font13pt whiteColor marginNone"><a :href="item.url" target="_blank"><b>{{item.name}}</b></a></p>
                                    <p class="font10pt whiteColor marginNone marginBottomSmall" v-if="item.album">
                                        <a :href="item.album_url" target="_blank">
                                            <b v-if="item.duration">{{item.duration}}  </b>
                                            <b class="unbold">{{item.album}} - {{item.album_year}}</b>
                                        </a>
                                    </p>
                                    <p class="font10pt marginNone whiteColor marginBottomSmall" v-if="item.genres">
                                        <b class="unbold">{{item.genres}}</b>
                                    </p>
                                    <p class="font10pt marginNone whiteColor marginBottomSmall" v-if="item.info">
                                        <b class="unbold">{{item.info}}</b>
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
        cardTitle: { default: 'Топ 10' },
        cardDesc: { default : undefined },
        items: { default: -1 },
        listType: { default: "tracks" }
    },
}
</script>