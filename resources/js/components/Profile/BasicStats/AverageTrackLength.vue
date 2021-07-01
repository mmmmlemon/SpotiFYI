//AverageTrackLength
<template>
    <div class="row justify-content-center fadeInAnim marginVertical">
        <!-- средняя длина трека -->
        <div v-if="tracksMode === -1" class="col-11 col-md-6 paddingSides">
            <Loader />
        </div>
        <div v-else-if="tracksMode === false" class="col-11 col-md-6 paddingSides">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div v-else-if="tracksMode != -1" class="col-12 text-center fadeInAnim paddingSides">
            <div class="row justify-content-center">
                <div class="col-4 d-none d-md-block">
                    <i class="fas fa-ruler-horizontal mainColor" style="font-size: 16rem;"></i>
                </div>
                <div class="col-12 col-md-8">
                    <h3 class="text-right paddingSides">
                        Средняя продолжительность трека - 
                        <b class="mainColorHighlight2 borderUnderline">{{tracksMode}}</b>
                    </h3>
                </div>
            </div>
        </div>
        <div v-else>
             <Error type="small"/>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        tracksMode: {default: -1 },
    },
}
</script>