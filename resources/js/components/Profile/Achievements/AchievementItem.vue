<template>
    <div class="col-md-5 margin_sides padding_10">
        <div v-if="items == -1">
            <Loader />
        </div>
        <div v-else-if="items == false">
            <Error type="small" errorMessage="Не удалось загрузить данные"/>
        </div>
        <div v-else-if="items != -1 || items != false" class="">
            <div class="top10_image_card" :style="{backgroundImage: `url('${items['image']}')`}">
                    </div>
            <div class="row justify-content-center margin_sides">
                <div class="col-12">
                    <h4 class="text-center border_underline">{{cardTitle}}</h4>
                    <h6 class="text-center">{{cardSubtitle}}</h6>
                </div>
                <img :src="items['image']" 
                    class="album_icon_achievement rounded-circle" alt="">
                <div class="col-12">
                    <h5 class="text-center"><a target="_blank" :href="items['url']">{{items['title']}}</a></h5>
                    <p class="text-center" v-if="items['additionalInfo']">Дополнительная информация</p>
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
        cardTitle: { default: '' },
        cardSubtitle: { default: '' },
        items: { default: -1 },
    }
}
</script>