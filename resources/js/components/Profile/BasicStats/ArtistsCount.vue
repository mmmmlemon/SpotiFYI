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
            <div v-else-if="uniqueArtists != -1 && uniqueArtists != false" class="col-12 paddingSides marginVertical">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h3 class="text-center">В твоей библиотеке есть треки от</h3>
                        <h1 class="text-center borderUnderline mainColorHighlight2">{{uniqueArtists['countArtists']}}</h1>
                    </div>
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
    props: {
        uniqueArtists: { default: -1 },
    }
}
</script>