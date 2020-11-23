<template>
    <div class="row ">
        <div class="col-sm-12 padding_10">
            <!-- минуты -->
            <p>Всего в твою библиотеку добавлено
                <b style="font-size:25pt;" class="border_underline">
                     {{userLibraryTime['overallMinutes']}}
                </b> музыки.
            </p>
            <!-- часы -->
            <p v-if="userLibraryTime['overallHours'] != 0">
                <b v-if="userLibraryTime['overallDays'] == 0" class="unbold">Или </b> 
                <b v-else class="unbold">В других исчислениях это</b> 
                <b class="border_underline">{{userLibraryTime['overallHours']}}</b>
                <b v-if="userLibraryTime['overallDays'] == 0" class="unbold"> песен.</b>
            </p>
            <!-- дни -->
            <p v-if="userLibraryTime['overallDays'] != 0">
                <b v-if="userLibraryTime['overallMonths'] == 0" class="unbold">или </b>
                <b class="border_underline">{{userLibraryTime['overallDays']}}</b>
                <b v-if="userLibraryTime['overallMonths'] == 0" class="unbold"> песен.</b>
            </p>
            <!-- месяцы -->
            <p v-if="userLibraryTime['overallMonths'] != 0">или 
                <b style="font-size:18pt;" class="border_underline">
                    {{userLibraryTime['overallMonths']}}
                </b> песен.
            </p>
        </div>
        <hr>
        <div class="col-md-6">
            <h4 class="border_underline"><b>Пять твоих самых длинных песен</b></h4>
            <div class="row fade_in_anim" v-for="item in fiveLongest" :key="item.id">
                    <div class="col-2">
                        <a :href="item.url" target="_blank">
                            <img :src="item.cover" class="rounded-circle album_icon">
                        </a>
                    </div>
                    <div class="col-10">
                        <p class="font_10pt"><b>{{item.name}}</b></p>
                        <p class="font_10pt">{{item.duration}}</p>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
       props: {
        userLibraryTime: { default: false },
        fiveLongest: { default: false }
    }
}
</script>