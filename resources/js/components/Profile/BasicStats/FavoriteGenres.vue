//FavoriteGenres
<template>
    <div class="col-12 col-md-12 fadeInAnim marginVertical">
        <div class="row justify-content-center">
            <!-- лоадер -->
            <div v-if="favoriteGenres == -1">
                <Loader />
                <h6 class="text-center blinkingAnim">Анализирую треки...</h6>
                <p class="text-center">Это может занять некоторое время</p>
            </div>
            <!-- ошибка -->
            <div v-else-if="favoriteGenres == false">
                <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
            </div>
            <!-- предупреждение -->
            <div v-else-if="favoriteGenres == 'noTracks'" class="col-12">
                <Info type="small" infoMessage="Пока не достаточно данных для проведения анализа жанров."/>
            </div>
            <!-- контент -->
            <div v-else-if="favoriteGenres != -1 && favoriteGenres != false" class="col-11 col-md-11">
                <h3 class="text-center borderUnderline">Твои любимые жанры</h3>
                <h5 class="text-center">За последний месяц</h5>
                <!-- график с жанрами -->
                <div class="col-12" v-if="favoriteGenres !== -1 && favoriteGenres != false">
                    <BarChart :favoriteGenres="favoriteGenres" :backgroundColor="backgroundColor" :styles="style" label="Любимые жанры"/>
                </div>

            </div>
            <!-- ошибка -->
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
            //цвета для графика
            backgroundColor: ['#1b77b9','#1bb98a','#48b91b','#b9941b','#b91b1b','#b91bb1','#4a1bb9','#223586','#228638','#864f22'],
            style: {
                color: 'red',
            }
        }
    },
    props: {
        //любимые жанры
        favoriteGenres: { default: -1 },
    },
}

</script>