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
                <div class="col-12 col-md-8 text-left marginVertical">
                    <!-- минуты -->
                    <h3>Всего в твою библиотеку добавлено
                        <b class="borderUnderline mainColorHighlight2">
                            {{userLibraryTime['overallMinutes']}}
                        </b> музыки.
                    </h3>
                    <!-- часы -->
                    <h5 v-if="userLibraryTime['overallHours'] != 0">
                        <b v-if="userLibraryTime['overallDays'] == 0" class="unbold">Или </b> 
                        <b v-else class="unbold">В других исчислениях это</b> 
                        <b class="borderUnderline  mainColorHighlight2">{{userLibraryTime['overallHours']}}</b>
                        <b v-if="userLibraryTime['overallDays'] == 0" class="unbold"> песен.</b>
                    </h5>
                    <!-- дни -->
                    <h5 v-if="userLibraryTime['overallDays'] != 0">
                        <b v-if="userLibraryTime['overallMonths'] == 0" class="unbold">или </b>
                        <b class="borderUnderline  mainColorHighlight2">{{userLibraryTime['overallDays']}}</b>
                        <b v-if="userLibraryTime['overallMonths'] == 0" class="unbold"> песен.</b>
                    </h5>
                    <!-- месяцы -->
                    <h5 v-if="userLibraryTime['overallMonths'] != 0">или 
                        <b class="font18pt borderUnderline">
                            {{userLibraryTime['overallMonths']}}
                        </b> песен.
                    </h5>
                </div>
                <div class="d-none d-md-block col-md-4 text-center">
                    <i class="far fa-clock mainColor" style="font-size: 16rem;"></i>
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