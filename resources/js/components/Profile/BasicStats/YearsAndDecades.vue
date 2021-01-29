//YearsDecades
<template>
    <div class="col-11 justify-content-center fadeInAnim">
        <!-- лоадер -->
        <div v-if="yearsAndDecades === -1">
            <Loader />
        </div>
        <!-- ошибка -->
        <div v-else-if="yearsAndDecades === false">
            <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
        </div>
        <!-- контент -->
        <div v-else-if="yearsAndDecades != -1 && yearsAndDecades != false" class="col-12 greyCard paddingSides marginVertical">
            <!-- за все время -->
            <div v-if="type == 'alltime'">
                <p class="text-center">
                    Больше всего тебе нравится музыка <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxDecade']}}-ых</b>. 
                    В твоей библиотеке <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxDecadeSongs']}}</b> из этой эпохи.
                </p>
                <p :v-if="type == 'alltime'" class="text-center">
                     <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxYear']}}-ый</b> - твой любимый год. 
                    Тебе нравятся <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxYearSongs']}}</b> из этого года.
                </p>   
            </div>

            <!-- за месяц -->
            <div v-else>
                <p :v-else-if="type == 'month'" class="text-center">
                    В последнее время ты больше всего слушаешь музыку <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxDecade']}}-ых</b>. 
                    За последний месяц ты послушал <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxDecadeSongs']}}</b> из этой эпохи.
                </p>
                 <p :v-else-if="type == 'month'" class="text-center">
                    <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxYear']}}-ый</b> - твой любимый год. 
                   Ты прослушал <b class="unbold borderUnderline font16pt">{{yearsAndDecades['maxYearSongs']}}</b> из этого года.
                </p>
            </div>

            <br>
            <h5 class="text-center borderUnderline">Песни по десятилетиям</h5>
            <BarChart :favoriteGenres="yearsAndDecades['countDecades']" label="Песни по десятилетиям" :backgroundColor="yearsAndDecades['decadeColors']"/>
            <h5 class="text-center borderUnderline">Песни по годам</h5>
            <BarChart :favoriteGenres="yearsAndDecades['countYears']" label="Песни по годам" :backgroundColor="yearsAndDecades['yearColors']"/>
        </div>
        <div v-else>
            <Error type="small"/>
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