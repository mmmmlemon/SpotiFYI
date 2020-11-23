<template>
    <div class="row ">
        <div class="col-sm-8 padding_10">
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
        <div class="col-md-4">
            <p style="font-size: 10pt;"><b>Пять твоих самых длинных песен</b></p>
            <div class="row">
                    <div class="col-2">
                        <img src="/black.png" alt="" class="rounded-circle album_icon">
                    </div>
                    <div class="col-10">
                        <p style="font-size:10pt;">Band Name - Track Title (15:15)</p>
                    </div>
            
            </div>
            <div class="row">
                    <div class="col-2">
                        <img src="/black.png" alt="" class="rounded-circle album_icon">
                    </div>
                    <div class="col-10">
                        <p style="font-size:10pt;">Band Name - Track Title (15:15)</p>
                    </div>
            
            </div>
               <div class="row">
                    <div class="col-2">
                        <img src="/black.png" alt="" class="rounded-circle album_icon">
                    </div>
                    <div class="col-10">
                        <p style="font-size:10pt;">Band Name - Track Title (15:15)</p>
                    </div>
            
            </div>
               <div class="row">
                    <div class="col-2">
                        <img src="/black.png" alt="" class="rounded-circle album_icon">
                    </div>
                    <div class="col-10">
                        <p style="font-size:10pt;">Band Name - Track Title (15:15)</p>
                    </div>
            
            </div>
               <div class="row">
                    <div class="col-2">
                        <img src="/black.png" alt="" class="rounded-circle album_icon">
                    </div>
                    <div class="col-10">
                        <p style="font-size:10pt;">Band Name - Track Title (15:15)</p>
                    </div>
            
            </div>
          
        </div>
        <div class="col-12 time_fact">
            <h2>За это время Галилео Галилей загалелеил галереи.</h2>
        </div>
    </div>
</template>

<script>
export default {
       props: {
        userLibraryTime: { default: false, string: Array},

    }
}
</script>