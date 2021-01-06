<template>
    <div class="col-md-4 padding_10">
        <div v-if="type === false">
            <Error type="small" errorMessage="Не указан тип компонента" />
        </div>
        <div v-else>
            <div v-if="itemCount == false">
                <Error v-if="type === 'tracks'" type="small" errorMessage = "Не удалось загрузить данные по трекам"/>
                <Error v-if="type === 'albums'" type="small" errorMessage = "Не удалось загрузить данные по альбомам"/>
                <Error v-if="type === 'artists'" type="small" errorMessage = "Не удалось загрузить подписки"/>
            </div>
            <div v-if="itemCount != false">
                <div v-if="itemCount == -1">
                    <Loader />
                </div>
                <div v-else-if="itemCount >= minItemCount" class="grey_card_trans padding_10 fade_in_slow_anim">
                    <h4 v-if="type === 'tracks'" class="text-center border_underline">Треки - <b>{{itemCount}}</b></h4>
                    <h4 v-if="type === 'albums'" class="text-center border_underline">Альбомы - <b>{{itemCount}}</b></h4>
                    <h4 v-if="type === 'artists'" class="text-center border_underline">Подписки - <b>{{itemCount}}</b></h4>
                    <div v-if="lastFiveItems == false">
                        <Error v-if="type === 'tracks'" type="small" errorMessage = "Не удалось загрузить треки"/>
                        <Error v-if="type === 'albums'" type="small" errorMessage = "Не удалось загрузить альбомы"/>
                        <Error v-if="type === 'artists'" type="small" errorMessage = "Не удалось загрузить подписки"/>
                    </div>
                    <div v-else-if="lastFiveItems == -1">
                        <!-- <Loader /> -->
                    </div>
                    <div v-else-if="lastFiveItems.length > 0" class="col-md-12 fade_in_slow_anim">  
                            <div class="col-md-12">
                                <p v-if="type === 'tracks'" style="font-size: 10pt;" class="text-center">Последние добавленные треки</p>
                                <p v-if="type === 'albums'" style="font-size: 10pt;" class="text-center">Последние добавленные альбомы</p>
                                <p v-if="type === 'artists'" style="font-size: 10pt;" class="text-center">Некоторые из твоих подписок</p>
                            </div>
                            <div class="row justify-content-center">
                            <div data-toggle="tooltip" :data-title="item.name" data-placement="bottom" class="col-2 fade_in_slow_anim" 
                                v-for="item in lastFiveItems" :key="item.id">
                                <a :href="item.url" target="_blank">
                                    <img class="rounded-circle album_icon" :src="item.cover">
                                </a>
                            </div>
                            </div>
                    </div>  
                </div>
                <div v-else>
                    <Error type="small"/>
                </div>  
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        type: { default: false, string: String},
        itemCount: { default: -1, type: Number },
        lastFiveItems: { default: -1 },
        minItemCount: { default: 10, type: Number},
    }
}
</script>