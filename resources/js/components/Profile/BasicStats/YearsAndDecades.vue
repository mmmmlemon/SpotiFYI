<template>
    <div class="col-11 justify-content-center">
        <div v-if="yearsAndDecades === -1">
            <Loader />
        </div>
        <div v-else-if="yearsAndDecades === false">
            <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
        </div>
        <div v-else-if="yearsAndDecades != -1 && yearsAndDecades != false" class="grey_card padding_10 margin_vertical">

            <div v-if="type == 'alltime'">
                <p class="text-center">Больше всего тебе нравится музыка <b class="unbold border_underline font_16pt">{{yearsAndDecades['maxDecade']}}-ых</b>. 
                    В твоей библиотеке <b class="unbold border_underline font_16pt">{{yearsAndDecades['maxDecadeSongs']}}</b> из этой эпохи.</p>
                 <p :v-if="type == 'alltime'" class="text-center"><b class="unbold border_underline font_16pt">{{yearsAndDecades['maxYear']}}-ый</b> - твой любимый год. 
                    Тебе нравятся <b class="unbold border_underline font_16pt">{{yearsAndDecades['maxYearSongs']}}</b> из этого года.</p>   
            </div>

            <div v-else>
                <p :v-else-if="type == 'month'" class="text-center">В последнее время ты больше всего слушаешь музыку <b class="unbold border_underline font_16pt">{{yearsAndDecades['maxDecade']}}-ых</b>. 
                    За последний месяц ты послушал <b class="unbold border_underline font_16pt">{{yearsAndDecades['maxDecadeSongs']}}</b> из этой эпохи.</p>
                 <p :v-else-if="type == 'month'" class="text-center"><b class="unbold border_underline font_16pt">{{yearsAndDecades['maxYear']}}-ый</b> - твой любимый год. 
                    Были прослушаны <b class="unbold border_underline font_16pt">{{yearsAndDecades['maxYearSongs']}}</b> из этого года.</p>
            </div>

            <br>
            <h5 class="text-center border_underline">Песни по десятилетиям</h5>
            <BarChart :favoriteGenres="yearsAndDecades['countDecades']" label="Песни по десятилетиям" :backgroundColor="yearsAndDecades['decadeColors']"/>
            <h5 class="text-center border_underline">Песни по годам</h5>
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