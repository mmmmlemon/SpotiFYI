<template>
    <div class="justify-content-center">
        <!-- лоадер -->
        <div class="col-12" v-if="items == -1">
            <Loader />
            <h6 class="text-center blinking_anim" v-if="items == -1">Загружаю последние треки...</h6>
            <p class="font_10pt text-center">Это может занять некоторое время</p>
        </div>
        <!-- список -->
        <div v-else-if="items != -1 && items != false" class="сol-12 margin_vertical fade_in_anim">
            <!-- вывод элементов списка в цикле -->
            <div class="col-12 justify-content-center padding_10 margin_vertical" v-for="item in items['tracks']" :key="item.id">
                <ListItem :item="item"/>
            </div>
        </div>
        <!-- ошибка -->
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