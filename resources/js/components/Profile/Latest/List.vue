<template>
    <div class="justify-content-center">
        <div class="col-12" v-if="items == -1">
            <Loader />
            <h6 class="text-center blinking_anim" v-if="items == -1">Загружаю последние треки...</h6>
            <p class="font_10pt text-center">Это может занять некоторое время</p>
        </div>
        <div v-else-if="items != -1 && items != false" class="сol-12 padding_10 margin_vertical fade_in_anim">
            <!-- <div class="top10_image_card" :style="{backgroundImage: `url('${items['backgroundImage']}')`}">
            </div> -->
            <div class="col-12 padding_10 justify-content-center margin_vertical" v-for="item in items['tracks']" :key="item.id">

                <ListItem :item="item"/>

            </div>
        </div>
        <div v-else>
            <Error errorMessage="Не удалось загрузить треки"/>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        items: { default: -1 },
    }
}
</script>