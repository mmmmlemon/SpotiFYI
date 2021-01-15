<template>
    <div class="col-12 col-md-5 marginSides marginVertical paddingSides">
        <div v-if="items == -1">
            <Loader />
        </div>
        <div v-else-if="items == false">
            <Error type="small" errorMessage="Не удалось загрузить данные"/>
        </div>
        <div v-else-if="items != -1 || items != false" class="">
            <BackgroundImage :backgroundImageUrl="items['image']"/>
            <div class="row justify-content-center fadeInAnim marginSides">
                <div class="col-12">
                    <h4 class="text-center borderUnderline">{{cardTitle}}</h4>
                    <h6 class="text-center" v-if="cardSubtitle != ''">{{cardSubtitle}}</h6>
                </div>
                <img :src="items['image']" 
                    class="rounded-circle albumIconAchievement" alt="">
                <div class="col-12">
                    <h5 class="text-center achievementTitle"><a target="_blank" :href="items['url']">{{items['title']}}</a></h5>
                    <h6 class="text-center achievementSubtitle"><a target="_blank" :href="items['url']" v-if="items['album']">{{items['album']}}</a></h6>
                    <p class="text-center achievementAdditional" v-if="items['additionalInfo']">{{items['additionalInfo']}}</p>
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