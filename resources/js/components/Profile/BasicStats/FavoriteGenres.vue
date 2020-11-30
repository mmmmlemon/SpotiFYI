<template>
    <div class="row justify-content-center">
        <div class="col-12 grey_card padding_10">
            <h4 class="text-center border_underline">Твои любимые жанры</h4>
            <p class="text-center">На основе того что ты слушаешь последний месяц</p>
            <FavoriteGenresChart :favoriteGenres="favoriteGenres"/>
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