//Top10Items
<template>
        <div class="col-12 paddingSides" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visible === false}">
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
            <div v-else-if="items != -1 || items != false" class="col-12 fadeInAnim paddingSides" style="margin-bottom: 3rem;">
                 <BackgroundImage bgStyle="top10ImageCard fadeInAnimBg" :backgroundImageUrl="items['backgroundImage']" v-if="visible === true"/>
                 <div class="col-12 fadeInAnimSlow" v-if="visible === true">
                    <h3 v-bind:class="{'text-left': orientation === 'left', 'text-right': orientation === 'right',}" class="borderUnderline goUpAnimSlow"><b>{{cardTitle}}</b></h3>
                    <p v-bind:class="{'text-right': orientation === 'left', 'text-left': orientation === 'right',}" class="goUpAnimSlow" v-if="cardDesc != undefined">{{cardDesc}}</p>
                    <hr class="goUpAnimSlow">
                    <p class="goUpAnimSlow text-center" v-if="desc != null">{{desc}}</p>
                    <div class="row fadeInAnim justify-content-center fadeInAnim"  v-if="items != undefined">         
                        <div class="col-12" style="margin-top: 2.5rem;">
                            <div class="row justify-content-center text-center">
                                <div class="col-12 col-md-6" v-if="visible === true">
                                    <div class="row justify-content-center">
                                        <div v-for="(item, index) in items['items']" :key="index" class="col-12">
                                            <div class="row jusitify-content-center goUpAnimSlow" v-if="item.count > 0 && item.count <= 5">
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
                                                    <h6 class="top10Album" v-if="item.album">
                                                        <a :href="item.album_url" target="_blank">
                                                            {{item.album}} ({{item.album_year}})
                                                        </a>
                                                    </h6>
                                                    <h5 class="top10Name textShadow" v-if="item.duration" v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                        <b>{{item.duration}}</b>
                                                    </h5>
                                                    <h5 class="top10Name textShadow" v-if="item.info" v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                        <b>{{item.info}}</b>
                                                    </h5>
                                                    <h6 class="top10Album" v-if="item.genres">
                                                            {{item.genres}}
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr v-if="item.count > 0 && item.count <= 5" class="goUpAnimSlow">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6" v-if="visible === true">
                                        <div class="row justify-content-center">
                                            <div v-for="(item, index) in items['items']" :key="index" class="col-12">
                                                <div class="row jusitify-content-center goUpAnimSlow" v-if="item.count > 5 && item.count <= 10">
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
                                                        <h6 class="top10Album" v-if="item.album">
                                                            <a :href="item.album_url" target="_blank">
                                                                {{item.album}} ({{item.album_year}})
                                                            </a>
                                                        </h6>
                                                        <h5 class="top10Name textShadow" v-if="item.duration" v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                            <b>{{item.duration}}</b>
                                                        </h5>
                                                        <h5 class="top10Name textShadow" v-if="item.info" v-bind:class="{'gold': item.count === 1, 'silver': item.count === 2, 'bronze': item.count === 3}">
                                                            <b>{{item.info}}</b>
                                                        </h5>
                                                        <h6 class="top10Album" v-if="item.genres">
                                                            {{item.genres}}
                                                        </h6>
                                                    </div>
                                                </div>
                                                <hr v-if="item.count > 5 && item.count <= 10" class="goUpAnimSlow">
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

    data: () => {
        return {
            visible: false,
        }
    },

    props: {
        cardTitle: { default: 'Топ 10' },
        cardDesc: { default : undefined },
        items: { default: -1 },
        listType: { default: "tracks" },
        orientation: { default: 'left'},
        visibleProp: { default: false},
        desc: { default: null },
    },

    computed: {
        //видимость карточки
        setVisible: {
            get() {
                this.visible = this.visibleProp;
            },
            set(value){
                this.visible = value;
            }
        },
    },

    methods: {
        //при скролле страницы показать карточку когда она будет 
        //в поле видимости
        handleScroll: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setVisible = true;
            }
            return el.getBoundingClientRect().top < 700;   
        }
    }
}
</script>