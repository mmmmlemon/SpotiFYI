<template>
    <div class="col-12 justify-content-center fadeInAnim">
        <div v-if="uniqueArtists === -1">
            <Loader />
        </div>
        <div v-else-if="uniqueArtists === false">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div v-else-if="uniqueArtists != -1 && uniqueArtists != false" class="col-12 paddingSides marginVertical">
            <BackgroundImage :backgroundImageUrl="uniqueArtists['artistImageUrl']"/>
            <BackgroundImageFront />
            <h6 class="text-center">В твою библиотеку добавлены треки от</h6>
            <h1 class="text-center borderUnderline">{{uniqueArtists['countArtists']}}</h1>
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