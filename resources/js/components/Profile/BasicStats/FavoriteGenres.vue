<template>
    <div class="col-12 justify-content-center fadeInAnim">
        <div v-if="favoriteGenres == -1">
            <Loader />
            <h6 class="text-center blinkingAnim">Анализирую треки...</h6>
            <p class="text-center font10pt">Это может занять некоторое время</p>
        </div>
        <div v-else-if="favoriteGenres == false">
            <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
        </div>
        <div v-else-if="favoriteGenres == 'noTracks'" class="greyCard paddingSides">
            <Info type="small" infoMessage="Пока не достаточно данных для проведения анализа жанров."/>
        </div>
        <div v-else-if="favoriteGenres != -1 && favoriteGenres != false" class="greyCard paddingSides marginVertical">
            <h4 class="text-center borderUnderline">Твои любимые жанры</h4>
            <p class="text-center">На основе того что ты слушаешь последний месяц</p>
            <BarChart :favoriteGenres="favoriteGenres" :backgroundColor="backgroundColor" label="Любимые жанры"/>
        </div>
        <div v-else>
            <Error type="small"/>
        </div>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            //цвета для графика
            backgroundColor: ['#1b77b9','#1bb98a','#48b91b','#b9941b','#b91b1b','#b91bb1','#4a1bb9','#223586','#228638','#864f22'],
        }
    },
    props: {
        //любимые жанры
        favoriteGenres: { default: -1 },
    },
}

</script>