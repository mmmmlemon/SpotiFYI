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
                        <div class="col-12" style="margin-top: 2.5rem;">
                            <div class="row justify-content-center text-center">
                                <div class="col-12 col-md-6">
                                    <div class="row justify-content-center">
                                        <div v-for="(item, index) in items['items']" :key="index" class="col-12">
                                            <div class="row jusitify-content-center" v-if="item.count > 0 && item.count <= 5">
                                                <div class="col-4 text-left top10Item">
                                                    <b class="btn btn-square rounded-circle top10Number"
                                                     v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                        {{item.count}}
                                                     </b>
                                                    <a :href="item.url" target="_blank">
                                                        <img :src="item.image" class="rounded-circle top10Cover" style="width: 50%;"
                                                         v-bind:class="{'gold goldShineAnim': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                    </a>
                                                </div>
                                                <div class="col-8 text-right">
                                                    <h6 class="textShadow top10Name" v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                        <a :href="item.url" target="_blank">
                                                            <b>{{item.name}}</b>
                                                        </a>  
                                                    </h6>
                                                    <h6 class="top10Album">
                                                        <a :href="item.album_url" target="_blank">
                                                            {{item.album}} ({{item.album_year}})
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr v-if="item.count > 0 && item.count <= 5">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                        <div class="row justify-content-center">
                                            <div v-for="(item, index) in items['items']" :key="index" class="col-12">
                                                <div class="row jusitify-content-center" v-if="item.count > 5 && item.count <= 10">
                                                    <div class="col-4 text-left top10Item">
                                                        <b class="btn btn-square rounded-circle top10Number"
                                                        v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                            {{item.count}}
                                                        </b>
                                                        <a :href="item.url" target="_blank">
                                                            <img :src="item.image" class="rounded-circle top10Cover" style="width: 50%;"
                                                            v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                        </a>
                                                    </div>
                                                    <div class="col-8 text-right">
                                                        <h6 class="textShadow top10Name" v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                            <a :href="item.url" target="_blank">
                                                                <b>{{item.name}}</b>
                                                            </a>  
                                                        </h6>
                                                        <h6 class="top10Album">
                                                            <a :href="item.album_url" target="_blank">
                                                                {{item.album}} ({{item.album_year}})
                                                            </a>
                                                        </h6>
                                                    </div>
                                                </div>
                                                <hr v-if="item.count > 5 && item.count <= 10">
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