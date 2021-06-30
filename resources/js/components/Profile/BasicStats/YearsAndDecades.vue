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
                </div>
                <!-- за месяц -->
                <div v-else>
                    <p :v-else-if="type == 'month'" class="text-center">
                        В последнее время ты больше всего слушаешь музыку <b class="borderUnderline">{{yearsAndDecades['maxDecade']}}-ых</b>. 
                        За последний месяц было прослушано <b class="borderUnderline">{{yearsAndDecades['maxDecadeSongs']}}</b> из этой эпохи.
                    </p>
                    <p :v-else-if="type == 'month'" class="text-center">
                        <b class="borderUnderline">{{yearsAndDecades['maxYear']}}-ый</b> - твой любимый год в последнее время. 
                        Было прослушано <b class="borderUnderline">{{yearsAndDecades['maxYearSongs']}}</b> вышедших в этом году.
                    </p>
                </div>

                <br>
                <h5 class="text-center">А вот так это выглядит на графиках</h5>
                <h5 class="text-center borderUnderline">Песни по десятилетиям</h5>
                <BarChart :favoriteGenres="yearsAndDecades['countDecades']" label="Песни по десятилетиям" :backgroundColor="yearsAndDecades['decadeColors']"/>
                <h5 class="text-center borderUnderline">Песни по годам</h5>
                <BarChart :favoriteGenres="yearsAndDecades['countYears']" label="Песни по годам" :backgroundColor="yearsAndDecades['yearColors']"/>
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