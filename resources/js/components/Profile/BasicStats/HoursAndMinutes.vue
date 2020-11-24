<template>
    <div>
        <div v-if="userLibraryTime === -1">
            <Loader />
        </div>
        <div v-else-if="userLibraryTime === false">
            <Error type="small" errorMessage="Не удалось загрузить треки"/>
        </div>
        <div v-else-if="userLibraryTime != -1 && userLibraryTime != false" class="col-sm-12 text-center">
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
        <div v-else>
            <Error type="small" errorMessage="Неизвестная ошибка" />
        </div>
        <hr>
    </div>
</template>

<script>
export default {
    props: {
        userLibraryTime: { default: false },
    }
}
</script>