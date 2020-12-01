<template>
    <div>
        <div v-if="favoriteGenres === -1">
            <Loader />
        </div>
        <div v-else-if="favoriteGenres === false">
            <Error type="small" errorMessage="Не удалось произвести анализ треков"/>
        </div>
        <div v-else-if="favoriteGenres != -1 && favoriteGenres != false" class="col-12 grey_card padding_10 margin_vertical">
            <h4 class="text-center border_underline">Твои любимые жанры</h4>
            <p class="text-center">На основе того что ты слушаешь последний месяц</p>
            <FavoriteGenresChart :favoriteGenres="favoriteGenres"/>
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
        favoriteGenres: { default: -1 },
    },
}

</script>