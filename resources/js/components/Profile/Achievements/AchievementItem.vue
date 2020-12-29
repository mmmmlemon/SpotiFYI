<template>
    <div>
        <div v-if="items == -1">
            <Loader />
        </div>
        <div v-else-if="items == false">
            <Error type="small" errorMessage="Не удалось загрузить данные"/>
        </div>
        <div v-else-if="items != -1 || items != false" class="col-md-5 grey_card margin_sides">
            <div class="top10_image_card" style="background-image: url('https://www.opinion-internationale.com/wp-content/uploads/2019/09/abbeyroad5_uk.jpg');">
                    </div>
            <div class="row justify-content-center margin_sides">
                <div class="col-12">
                    <h4 class="text-center border_underline">Самый самый что-то там</h4>
                </div>
                <img src="https://www.opinion-internationale.com/wp-content/uploads/2019/09/abbeyroad5_uk.jpg" 
                    class="album_icon_achievement rounded-circle" alt="">
                <div class="col-12">
                    <h5 class="text-center"><a href="">Исполнитель - Название трека</a></h5>
                    <p class="text-center">Дополнительная информация</p>
                </div>
            </div>
        </div>
        <div v-else>
            <Error type="x-small" errorMessage="Нечего показывать. Параметр items пустой."/>
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