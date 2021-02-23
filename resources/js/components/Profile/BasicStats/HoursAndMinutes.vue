//HoursAndMinutes
<template>
    <div class="col-11 fadeInAnim">
        <div class="justify-content-center">
            <div v-if="userLibraryTime === -1">
                <Loader />
            </div>
            <div v-else-if="userLibraryTime === false">
                <Error type="small" errorMessage="Не удалось загрузить треки"/>
            </div>
            <div v-else-if="userLibraryTime != -1 && userLibraryTime != false" class="row justify-content-center">
                <div class="col-12 text-center marginVertical">
                    <BackgroundImage :backgroundImageUrl="userLibraryTime['coverImageUrl']"/>
                    <BackgroundImageFront />
                    <!-- минуты -->
                    <p>Всего в твою библиотеку добавлено
                        <b class="borderUnderline font25pt">
                            {{userLibraryTime['overallMinutes']}}
                        </b> музыки.
                    </p>
                    <!-- часы -->
                    <p v-if="userLibraryTime['overallHours'] != 0">
                        <b v-if="userLibraryTime['overallDays'] == 0" class="unbold">Или </b> 
                        <b v-else class="unbold">В других исчислениях это</b> 
                        <b class="borderUnderline">{{userLibraryTime['overallHours']}}</b>
                        <b v-if="userLibraryTime['overallDays'] == 0" class="unbold"> песен.</b>
                    </p>
                    <!-- дни -->
                    <p v-if="userLibraryTime['overallDays'] != 0">
                        <b v-if="userLibraryTime['overallMonths'] == 0" class="unbold">или </b>
                        <b class="borderUnderline">{{userLibraryTime['overallDays']}}</b>
                        <b v-if="userLibraryTime['overallMonths'] == 0" class="unbold"> песен.</b>
                    </p>
                    <!-- месяцы -->
                    <p v-if="userLibraryTime['overallMonths'] != 0">или 
                        <b class="font18pt borderUnderline">
                            {{userLibraryTime['overallMonths']}}
                        </b> песен.
                    </p>
                </div>
            </div>
            <!-- ошибка -->
            <div v-else>
                <Error type="small"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        userLibraryTime: { default: false },
    }
}
</script>