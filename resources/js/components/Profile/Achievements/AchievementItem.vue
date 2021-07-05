//AchievementItem
<template>
    <div class="col-12" style="margin-top: 3rem;">
        <!-- лоадер -->
        <div v-if="items == -1">
            <Loader />
        </div>
        <!-- ошибка -->
        <div v-else-if="items == false">
            <Error type="small" errorMessage="Не удалось загрузить данные"/>
        </div>
        <!-- контент -->
        <div v-else-if="items != -1 || items != false" class="">
            <div class="d-none d-md-flex row justify-content-center fadeInAnim" v-if="orientation === 'left'">
                <div class="col-9">
                    <!-- заголовок карточки -->
                    <h4 class="text-left borderUnderline"><b>{{cardTitle}}</b></h4>
                    <!-- подзаголовок -->
                    <h6 class="text-right" v-if="cardSubtitle != ''">{{cardSubtitle}}</h6>
                </div>
                <div class="col-11 col-md-4 text-left">
                    <!-- картинка -->
                    <img :src="items['image']" 
                        class="rounded-circle achievementIcon" alt="">
                </div>
                <!-- прочая информация -->
                <div class="col-11 col-md-6">
                    <h5 class="text-left achievementItemTitle textShadow"  style="margin-bottom: 0;"><b><a target="_blank" :href="items['url']">{{items['title']}}</a></b></h5>
                    <p class="text-center" style="margin-top: 0;" v-if="items['additionalInfo']"><b>{{items['additionalInfo']}}</b></p>
                    <h6 class=""><a target="_blank" :href="items['url']" v-if="items['album']">{{items['album']}}</a></h6>
                    <h5>Тебе нравятся <b class="borderUnderline mainColorHighlight2">{{items['trackCount']}}</b> этого исполнителя.</h5>
                    <h6>Например, <b class="textShadow"><a target="_blank" :href="items['trackUrl']">{{items['selectedTrack']}}</a></b></h6>
                </div>
            </div>

            <div class="d-none d-md-flex row justify-content-center fadeInAnim" v-if="orientation === 'right'">
                <div class="col-9">
                    <!-- заголовок карточки -->
                    <h4 class="text-right borderUnderline"><b>{{cardTitle}}</b></h4>
                    <!-- подзаголовок -->
                    <h6 class="text-left" v-if="cardSubtitle != ''">{{cardSubtitle}}</h6>
                </div>
                <!-- прочая информация -->
                <div class="col-11 col-md-6">
                    <h5 class="text-left achievementItemTitle textShadow"  style="margin-bottom: 0;"><b><a target="_blank" :href="items['url']">{{items['title']}}</a></b></h5>
                    <p class="text-center" style="margin-top: 0;" v-if="items['additionalInfo']"><b>{{items['additionalInfo']}}</b></p>
                    <h6 class=""><a target="_blank" :href="items['url']" v-if="items['album']">{{items['album']}}</a></h6>
                    <h5>Тебе нравятся <b class="borderUnderline mainColorHighlight2">{{items['trackCount']}}</b> этого исполнителя.</h5>
                    <h6>Например, <b class="textShadow"><a target="_blank" :href="items['trackUrl']">{{items['selectedTrack']}}</a></b></h6>
                </div>
                <div class="col-11 col-md-4 text-right">
                    <!-- картинка -->
                    <img :src="items['image']" 
                        class="rounded-circle achievementIcon" alt="">
                </div>
            </div>

            <div class="d-flex d-md-none row justify-content-center fadeInAnim">
                <div class="col-9">
                    <!-- заголовок карточки -->
                    <h4 class="text-center borderUnderline"><b>{{cardTitle}}</b></h4>
                    <!-- подзаголовок -->
                    <h6 class="text-center" v-if="cardSubtitle != ''">{{cardSubtitle}}</h6>
                </div>
                <div class="col-11 text-center">
                    <!-- картинка -->
                    <img :src="items['image']" 
                        class="rounded-circle achievementIcon" alt="">
                </div>
                <!-- прочая информация -->
                <div class="col-11">
                    <h5 class="text-center achievementItemTitle textShadow"  style="margin-bottom: 0;"><b><a target="_blank" :href="items['url']">{{items['title']}}</a></b></h5>
                    <p class="text-center" style="margin-top: 0;" v-if="items['additionalInfo']"><b>{{items['additionalInfo']}}</b></p>
                    <h6 class="text-center"><a target="_blank" :href="items['url']" v-if="items['album']">{{items['album']}}</a></h6>
                    <h5 class="text-center">Тебе нравятся <b class="borderUnderline mainColorHighlight2">{{items['trackCount']}}</b> этого исполнителя.</h5>
                    <h6 class="text-center">Например, <b class="textShadow"><a target="_blank" :href="items['trackUrl']">{{items['selectedTrack']}}</a></b></h6>
                </div>
          
            </div>




        </div>
        <!-- ошибка -->
        <div v-else>
            <Error type="x-small" errorMessage="Нечего показывать. Параметр items пустой."/>
        </div>
    </div>

</template>
<script>
export default {
    props: {
        orientation: {default:'left'},
        cardTitle: { default: '' },
        cardSubtitle: { default: '' },
        items: { default: -1 },
    }
}
</script>