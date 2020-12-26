<template>
    <div class="col-12 justify-content-center fade_in_anim">
        <div v-if="uniqueArtists === -1">
            <Loader />
        </div>
        <div v-else-if="uniqueArtists === false">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div v-else-if="uniqueArtists != -1 && uniqueArtists != false" class="col-12 padding_10 margin_vertical">
            <div class="image_card" :style="{backgroundImage: `url('${uniqueArtists['artistImageUrl']}')`}">
            </div>
            <div class="image_card_front"></div>
            <h6 class="text-center">В твою библиотеку добавлены треки от</h6>
            <h1 class="text-center border_underline">{{uniqueArtists['countArtists']}}</h1>
        </div>
        <div v-else>
            <Error type="small"/>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        uniqueArtists: { default: -1 },
    }
}
</script>