//Top10Items
<template>
        <div class="col-12 paddingSides">
            <!-- лоадер -->
            <div v-if="items == -1">
                <Loader />
            </div>
            <!-- ошибка -->
            <div v-else-if="items == false">
                <Error type="small" errorMessage="Не удалось загрузить треки"/>
            </div>
            <!-- предупреждение -->
            <div v-else-if="items == 'noTracks'">
                <Info type="small" infoMessage="Пока что мало данных для Топ-10"/>
            </div>
            <!-- контент -->
            <div v-else-if="items != -1 || items != false" class="col-12 fadeInAnim paddingSides"> 
                 <div class="col-12">
                    <h3 v-bind:class="{'text-left': orientation === 'left', 'text-right': orientation === 'right',}" class="borderUnderline"><b>{{cardTitle}}</b></h3>
                    <p v-bind:class="{'text-right': orientation === 'left', 'text-left': orientation === 'right',}" v-if="cardDesc != undefined">{{cardDesc}}</p>
                    <!-- первые три -->
                    <div class="row fadeInAnim justify-content-center fadeInAnim"  v-if="items != undefined">
                        <!-- №1 -->
                        <div class="col-8 col-md-4 text-left" style="margin-top:2rem;">
                            <div class="col-12">
                                <a :href="items['items'][0].url" target="_blank">
                                        <img :src="items['items'][0].image" class="rounded-circle top10One">
                                </a>
                                <a class="btn btn-square rounded-circle top10OneNumber">1</a>
                            </div>
                            <div class="col-12 text-center">
                                <a :href="items['items'][0].url" target="_blank">
                                    <h5 class="top10Name textShadow">
                                        <b>{{items['items'][0].name}}</b>
                                    </h5>
                                </a>
                                <a :href="items['items'][0].album_url" target="_blank">
                                    <h6 class="top10Name textShadow">
                                        {{items['items'][0].album}} ({{items['items'][0].album_year}})
                                    </h6>
                                </a>
                            </div>
                        </div>
                        <!-- №2 -->
                        <div class="col-8 col-md-4 text-center" style="margin-top:2rem;">
                            <div class="col-12">
                                <a :href="items['items'][1].url" target="_blank">
                                        <img :src="items['items'][1].image" class="rounded-circle top10Two">
                                </a>
                                <a class="btn btn-square rounded-circle top10TwoNumber">2</a>
                            </div>
                            <div class="col-12 text-center">
                                <a :href="items['items'][1].url" target="_blank">
                                    <h5 class="top10Name textShadow">
                                        <b>{{items['items'][1].name}}</b>
                                    </h5>
                                </a>
                                <a :href="items['items'][1].album_url" target="_blank">
                                    <h6 class="top10Name textShadow">
                                        {{items['items'][1].album}} ({{items['items'][0].album_year}})
                                    </h6>
                                </a>
                            </div>
                        </div>
                        <!-- №3 -->
                        <div class="col-8 col-md-4 text-center" style="margin-top:2rem;">
                            <div class="col-12">
                                <a :href="items['items'][2].url" target="_blank">
                                    <img :src="items['items'][2].image" class="rounded-circle top10Three">
                                </a>
                                <a class="btn btn-square rounded-circle top10ThreeNumber">3</a>
                            </div>
                            <div class="col-12 text-center">
                                <a :href="items['items'][2].url" target="_blank">
                                    <h5 class="top10Name textShadow">
                                        <b>{{items['items'][2].name}}</b>
                                    </h5>
                                </a>
                                <a :href="items['items'][2].album_url" target="_blank">
                                    <h6 class="top10Name textShadow">
                                        {{items['items'][2].album}} ({{items['items'][0].album_year}})
                                    </h6>
                                </a>
                            </div>
                        </div>
                        <div class="col-12" style="margin-top: 2.5rem;">
                            <div class="row justify-content-center text-center">
                                <div class="col-6">
                                    <div class="row justify-content-center">
                                        <div v-for="(item, index) in items['items']" :key="index" class="col-12">
                                            <div class="row jusitify-content-center" v-if="item.count > 3 && item.count <= 7">
                                                <div class="col-4 text-right">
                                                    <b>{{item.count}}</b>
                                                    <a :href="item.url" target="_blank">
                                                        <img :src="item.image" class="rounded-circle" style="width: 50%;">
                                                    </a>
                                                </div>
                                                <div class="col-8 t">
                                                    <h6>
                                                        <b>{{item.name}}</b>
                                                    </h6>
                                                    <h6>{{item.album}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  <div class="col-6">
                                    <div class="row justify-content-center">
                                        <div v-for="(item, index) in items['items']" :key="index" class="col-12">
                                            <div class="row jusitify-content-center" v-if="item.count > 7 && item.count <= 10">
                                                <div class="col-4 text-right">
                                                    <b>{{item.count}}</b>
                                                    <a :href="item.url" target="_blank">
                                                        <img :src="item.image" class="rounded-circle" style="width: 50%;">
                                                    </a>
                                                </div>
                                                <div class="col-8 t">
                                                    <h6>
                                                        <b>{{item.name}}</b>
                                                    </h6>
                                                    <h6>{{item.album}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <Error type="x-small" errorMessage="Нечего показывать. Параметр items пустой."/>
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
        listType: { default: "tracks" },
        orientation: { default: 'left'},
    },
}
</script>