//ArtistsCounr
<template>
    <div class="col-11 justify-content-center fadeInAnim">
        <div class="row justify-content-center">
            <!-- лоадер -->
            <div v-if="uniqueArtists === -1">
                <Loader />
            </div>
            <!-- ошибка -->
            <div v-else-if="uniqueArtists === false">
                <Error type="small" errorMessage="Не удалось загрузить треки" />
            </div>
            <!-- контент -->
            <div v-else-if="uniqueArtists != -1 && uniqueArtists != false" class="col-11 paddingSides marginVertical">
            
                    <!-- фоновое изображение -->
                    <BackgroundImage :backgroundImageUrl="uniqueArtists['artistImageUrl']"/>
                    <!-- затемнение для фона -->
                    <BackgroundImageFront />
                    <h6 class="text-center">В твою библиотеку добавлены треки от</h6>
                    <h1 class="text-center borderUnderline">{{uniqueArtists['countArtists']}}</h1>
        
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
    props: {
        uniqueArtists: { default: -1 },
    }
}
</script>