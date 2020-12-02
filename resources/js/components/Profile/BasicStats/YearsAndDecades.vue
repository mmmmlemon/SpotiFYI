<template>
    <div>
        <div v-if="yearsAndDecades === -1">
            <Loader />
        </div>
        <div v-else-if="yearsAndDecades === false">
            <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
        </div>
        <div v-else-if="yearsAndDecades != -1 && yearsAndDecades != false" class="col-12 grey_card padding_10 margin_vertical">
            <p>Десятилетие - {{yearsAndDecades['maxDecade']}}</p>
            <p>Год - {{yearsAndDecades['maxYear']}}</p>
            <h5 class="text-center border_underline">Песни по десятилетиям</h5>
            <BarChart :favoriteGenres="yearsAndDecades['countDecades']" label="Песни по десятилетиям"/>
            <h5 class="text-center border_underline">Песни по годам</h5>
            <BarChart :favoriteGenres="yearsAndDecades['countYears']" label="Песни по годам"/>
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
    },
}
</script>