//YearsDecades
<template>
    <div class="col-12 fadeInAnim">
        <div class="row justify-content-center">
            <!-- лоадер -->
            <div v-if="yearsAndDecades === -1">
                <Loader />
            </div>
            <!-- ошибка -->
            <div v-else-if="yearsAndDecades === false">
                <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
            </div>
            <!-- контент -->
            <div v-else-if="yearsAndDecades != -1 && yearsAndDecades != false" class="col-11 greyCard paddingSides marginVertical">
                <!-- за все время -->
                <div v-if="type == 'alltime'" class="col-12 col-md-12">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8">
                            <h3 class="text-left">
                                Твоя любимая эпоха <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['maxDecade']}}-ые</b>. 
                                В твоей библиотеке <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['maxDecadeSongs']}}</b> из этого десятилетия.
                            </h3>
                            <h6>А это <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['percent']}}%</b> твоей библиотеки!</h6>
                            <h5 :v-if="type == 'alltime'" class="text-left">
                                <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['maxYear']}}-ый</b> - твой самый любимый год. 
                                Тебе нравятся <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['maxYearSongs']}}</b> вышедших в этом году.
                                <br><br>Например, <b class="textShadow"><a :href="yearsAndDecades['songOfYear']['url']" target="_blank">{{yearsAndDecades['songOfYear']['trackName']}}</a></b>
                            </h5> 
                        </div>
                        <div class="d-none d-md-block col-3">
                            <div class="row justify-content-center">
                                <div class="col-1">
                                    <img :src="yearsAndDecades['songOfYear']['cover']" class="rounded-circle albumIconBig greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][0]" class="rounded-circle albumIconBig2 greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][1]" class="rounded-circle albumIconBig3 greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][2]" class="rounded-circle albumIconBig4 greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][3]" class="rounded-circle albumIconBig5 greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][4]" class="rounded-circle albumIconBig6 greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][5]" class="rounded-circle albumIconBig7 greenShadow" alt="">
                                </div>
                                <div class="col-1">
                                    <img :src="yearsAndDecades['covers'][6]" class="rounded-circle albumIconBig8 greenShadow" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 class="text-center">А вот так это выглядит на графике</h5>
                    
                    <div class="col-12">
                        <!-- <h5 class="text-center borderUnderline">Песни по десятилетиям</h5> -->
                        <BarChart :favoriteGenres="yearsAndDecades['countDecades']" label="Песен из этой эпохи" :backgroundColor="yearsAndDecades['decadeColors']"
                                    :height="600"/>
                    </div>
                    <h5 class="text-center" style="margin-top: 6rem;">А вот твоя карта песен по годам <i class="far fa-chart-bar mainColor"></i></h5>
                    <div class="col-12 marginVertical">
                        <!-- <h5 class="text-center borderUnderline">Песни по годам</h5> -->
                        <BarChart :favoriteGenres="yearsAndDecades['countYears']" label="Песен в году" :backgroundColor="yearsAndDecades['yearColors']"
                                    :height="950"/>
                    </div>
                </div>
                <!-- за месяц -->
                <div v-else class="row justify-content-center">
                    <div :v-else-if="type == 'month'" class="col-12 col-md-9">
                        <h3 class="text-left">
                            В последнее время ты больше всего слушаешь музыку <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['max']}}-ых</b>. 
                        </h3>
                        <h5 class="text-left">
                            <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['maxSongs']}}</b> из этой эпохи {{yearsAndDecades['word']}} через тебя за последний месяц.
                        </h5>
                        <h5>Например, <b class="textShadow"><a :href="yearsAndDecades['maxSong']['url']" target="_blank">{{yearsAndDecades['maxSong']['trackName']}}</a></b>, 
                        вышедшая в <b class="borderUnderline mainColorHighlight2">{{yearsAndDecades['maxSong']['year']}}-ом</b> году.</h5>
                    </div>
                    <div class="d-none d-md-block col-md-3">
                        <div class="col-6">
                            <i v-if="yearsAndDecades['max'] < 1950" class="fas fa-music iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] >= 1950 && yearsAndDecades['max'] <= 1970" class="fas fa-record-vinyl iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] === 1980 || yearsAndDecades['max'] === 1990" class="fas fa-compact-disc iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] === 2000" class="fas fa-play-circle iconMonth"></i>
                            <i v-if="yearsAndDecades['max'] >= 2010" class="fas fa-cloud iconMonth"></i>
                        </div>
                        <div class="col-6">
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
    props: {
        //любимые жанры
        yearsAndDecades: { default: -1 },
        type: { default: 'alltime' },
    },
}
</script>