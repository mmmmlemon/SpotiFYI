//YearsDecades
<template>
    <div class="col-12 fadeInAnim" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visible === false}">
        <div class="row justify-content-center">
            <!-- лоадер -->
            <div v-if="yearsAndDecades === -1">
                <Loader />
            </div>
            <!-- ошибка -->
            <div v-else-if="yearsAndDecades === 'noTracks'">
                <br>
            </div>
            <!-- контент -->
            <div v-else-if="yearsAndDecades != -1 && yearsAndDecades != false && visible === true" class="col-11 paddingSides marginVertical fadeInAnimSlow">
                <!-- за все время -->
                <div v-if="type == 'alltime'" class="col-12 col-md-12">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8">
                            <h3 class="text-left slideLeftHours1">
                                Твоя любимая эпоха <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['maxDecade']}}-ые</b>. 
                                В твоей библиотеке <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['maxDecadeSongs']}}</b> из этого десятилетия.
                            </h3>
                            <h6 class="slideLeftHours1">А это <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['percent']}}%</b> твоей библиотеки!</h6>
                            <h5 :v-if="type == 'alltime'" class="text-left slideLeftHours1">
                                <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['maxYear']}}-ый</b> - твой самый любимый год. 
                                Тебе нравятся <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['maxYearSongs']}}</b> вышедших в этом году.
                                <br><br>Например, <b class="textShadow"><a :href="yearsAndDecades['songOfYear']['url']" target="_blank">{{yearsAndDecades['songOfYear']['trackName']}}</a></b>
                            </h5> 
                        </div>
                        <div class="d-none d-md-block col-3">
                            <div class="row justify-content-center">
                                <div class="col-1">
                                    <img :src="yearsAndDecades['songOfYear']['cover']" class="rounded-circle albumIconBig" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][0]" class="rounded-circle albumIconBig2" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][1]" class="rounded-circle albumIconBig3" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][2]" class="rounded-circle albumIconBig4" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][3]" class="rounded-circle albumIconBig5" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][4]" class="rounded-circle albumIconBig6" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][5]" class="rounded-circle albumIconBig7" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][6]" class="rounded-circle albumIconBig8" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="d-block d-md-none col-12 albumCoversMobile">
                            <div class="row justify-content-center">
                                <div class="col-1" >
                                    <img :src="yearsAndDecades['songOfYear']['cover']" class="rounded-circle albumIconBig" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][0]" class="rounded-circle albumIconBig2" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][1]" class="rounded-circle albumIconBig3" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null"> 
                                    <img :src="yearsAndDecades['covers'][2]" class="rounded-circle albumIconBig4" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][3]" class="rounded-circle albumIconBig5" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][4]" class="rounded-circle albumIconBig6" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][5]" class="rounded-circle albumIconBig7" alt="">
                                </div>
                                <div class="col-1" v-if="yearsAndDecades['covers'] !== null">
                                    <img :src="yearsAndDecades['covers'][6]" class="rounded-circle albumIconBig8" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 class="text-center goUpAnimSlow" v-if="chart === true">А вот так это выглядит на графике</h5>
                    
                    <div class="col-12" v-scroll="handleScrollChart">
                        <!-- <h5 class="text-center borderUnderline">Песни по десятилетиям</h5> -->
                        <div class="col-12 goUpAnimSlow" v-if="chart === true">
                            <BarChart :favoriteGenres="yearsAndDecades['countDecades']" label="Песен из этой эпохи" :backgroundColor="yearsAndDecades['decadeColors']"
                                :height="600"/>
                        </div>
                 
                    </div>
                    <h5 class="text-center goUpAnimSlow" style="margin-top: 6rem;" v-if="chartYears === true">А вот твоя карта песен по годам <i class="far fa-chart-bar mainColor"></i></h5>
                    <div class="col-12 marginVertical" v-scroll="handleScrollChartYears">
                        <div class="col-12 goUpAnimSlow" v-if="chartYears === true">
                            <!-- <h5 class="text-center borderUnderline">Песни по годам</h5> -->
                            <BarChart :favoriteGenres="yearsAndDecades['countYears']" label="Песен в году" :backgroundColor="yearsAndDecades['yearColors']"
                                    :height="950"/>
                        </div>
                
                    </div>
                </div>
                <!-- за месяц -->
                <div v-else class="row justify-content-center" v-scroll="handleScrollMonth">
                    <div :v-else-if="type == 'month'" class="col-12 col-md-9">
                        <h3 class="text-left slideLeftHours1">
                            В последнее время ты больше всего слушаешь музыку <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['max']}}-ых</b>. 
                        </h3>
                        <h5 class="text-left slideLeftHours1">
                            <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['maxSongs']}}</b> из этой эпохи {{yearsAndDecades['word']}} через тебя за последний месяц.
                        </h5>
                        <h5 class="slideLeftHours1">Например, <b class="textShadow"><a :href="yearsAndDecades['maxSong']['url']" target="_blank">{{yearsAndDecades['maxSong']['trackName']}}</a></b>, 
                        вышедшая в <b class="borderUnderline mainColorHighlight2 colorFadeIn">{{yearsAndDecades['maxSong']['year']}}-ом</b> году.</h5>
                    </div>
                    <div class="d-none d-md-block col-md-3 slideRightIcon">
                        <div class="col-6">
                            <i v-if="yearsAndDecades['max'] < 1950" class="fas fa-music iconMonth colorFadeIn"></i>
                            <i v-if="yearsAndDecades['max'] >= 1950 && yearsAndDecades['max'] <= 1970" class="fas fa-record-vinyl iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] === 1980 || yearsAndDecades['max'] === 1990" class="fas fa-compact-disc iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] === 2000" class="fas fa-play-circle iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] >= 2010" class="fas fa-cloud iconMonth"></i>
                        </div>
                        <div class="col-6">
                            <img :src="yearsAndDecades['maxSong']['cover']" class="rounded-circle albumIconBigMonth greenShadow" alt="">
                        </div>
                    </div>
                    <div class="d-block d-md-none col-md-3 text-right albumCoverMonthMobile">
                        <div class="col-6 slideRight">
                            <i v-if="yearsAndDecades['max'] < 1950" class="fas fa-music iconMonth colorFadeIn"></i>
                            <i v-if="yearsAndDecades['max'] >= 1950 && yearsAndDecades['max'] <= 1970" class="fas fa-record-vinyl iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] === 1980 || yearsAndDecades['max'] === 1990" class="fas fa-compact-disc iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] === 2000" class="fas fa-play-circle iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] >= 2010" class="fas fa-cloud iconMonth"></i>
                        </div>
                        <div class="col-6 slideRight">
                            <img :src="yearsAndDecades['maxSong']['cover']" class="rounded-circle albumIconBigMonth greenShadow" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <Error type="small"/>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            visible: false,
            chart: false,
            chartYears: false,
            month: false,
        }
    },

    props: {
        //любимые жанры
        yearsAndDecades: { default: -1 },
        type: { default: 'alltime' },
    },

    computed: {
        //видимость карточки
        setVisible: {
            get() {
                this.visible = false;
            },
            set(value){
                this.visible = value;
            }
        },
        setChart: {
            get() {
                this.chart = false;
            },
            set(value){
                this.chart = value;
            }
        },
        setChartYears: {
            get() {
                this.chartYears = false;
            },
            set(value){
                this.chartYears = value;
            }
        },
        setMonth: {
            get() {
                this.month = false;
            },
            set(value){
                this.month = value;
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
        },
        handleScrollChart: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setChart = true;
            }
            return el.getBoundingClientRect().top < 700;   
        },
        handleScrollChartYears: function (evt, el){
            if (el.getBoundingClientRect().top < 800) {
                this.setChartYears = true;
            }
            return el.getBoundingClientRect().top < 800;   
        },
        handleScrollMonth: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setMonth = true;
            }
            return el.getBoundingClientRect().top < 700;   
        },
    }
}
</script>