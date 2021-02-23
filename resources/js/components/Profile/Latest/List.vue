//List
<template>
    <div class="row justify-content-center">
        <!-- лоадер -->
        <div class="col-12" v-if="items == -1">
            <Loader />
            <h6 class="text-center blinkingAnim" v-if="items == -1">Загружаю последние треки...</h6>
            <p class="text-center font10pt ">Это может занять некоторое время</p>
        </div>
        <!-- список -->
        <div v-else-if="items != -1 && items != false" class="сol-12 fadeInAnim marginVertical">
            <div class="row justify-content-center">
                <!-- вывод элементов списка в цикле -->
                <div class="col-11 justify-content-center paddingSides marginVertical" v-for="item in items['tracks']" :key="item.id">
                    <ListItem :item="item"/>
                </div>
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