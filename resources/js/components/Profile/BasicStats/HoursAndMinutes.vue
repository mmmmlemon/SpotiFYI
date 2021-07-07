//HoursAndMinutes
<template>
    <div class="col-11 fadeInAnim marginVertical" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visible === false}">
        <div class="justify-content-center" v-if="visible === true">
            <div v-if="userLibraryTime === -1">
                <Loader />
            </div>
            <div v-else-if="userLibraryTime === false">
                <Error type="small" errorMessage="Не удалось загрузить треки"/>
            </div>
            <div v-else-if="userLibraryTime != -1 && userLibraryTime != false" class="row justify-content-center">
                <div class="col-12 col-md-8 text-left">
                    <!-- минуты -->
                    <h3 class="slideLeftHours1">Всего в твою библиотеку добавлено
                        <b class="borderUnderline mainColorHighlight2 colorHours1">
                            <!-- {{userLibraryTime['overallMinutes']}} -->
                            {{ overallMinutesAnimated }}
                        </b> музыки.
                    </h3>
                    <!-- часы -->
                    <h5 class="slideLeftHours2" v-if="userLibraryTime['overallHours'] != 0">
                        <b v-if="userLibraryTime['overallDays'] == 0" class="">Или </b> 
                        <b v-else>В других исчислениях это</b> 
                        <b class="borderUnderline mainColorHighlight2 colorHours2">{{ overallHoursAnimated }}</b>
                        <b v-if="userLibraryTime['overallDays'] == 0"> песен.</b>
                    </h5>
                    <!-- дни -->
                    <h5 class="slideLeftHours3" v-if="userLibraryTime['overallDays'] != 0">
                        <b v-if="userLibraryTime['overallMonths'] == 0" class="">или </b>
                        <b class="borderUnderline mainColorHighlight2 colorHours3">{{ overallDaysAnimated }}</b>
                        <b v-if="userLibraryTime['overallMonths'] == 0" class=""> песен.</b>
                    </h5>
                    <!-- месяцы -->
                    <h5 class="slideLeftHours4" v-if="userLibraryTime['overallMonths'] != 0">или 
                        <b class="borderUnderline mainColorHighlight2 colorHours4">
                            {{ overallMonthsAnimated }}
                        </b> песен.
                    </h5>
                </div>
                <div class="d-none d-md-block col-md-4 text-center">
                    <i class="far fa-clock mainColor slideRightIcon" style="font-size: 16rem;"></i>
                </div>
                <div class="d-block d-md-none col-md-4 text-center iconRightMobile">
                    <i class="far fa-clock mainColor slideRightIcon" style="font-size: 16rem;"></i>
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
    data: () => {
        return {
            visible: false,
            overallMinutes: 0,
            overallMinutesTweened: 0,
            overallHours: 0,
            overallHoursTweened: 0,
            overallDays: 0,
            overallDaysTweened: 0,
            overallMonths: 0,
            overallMonthsTweened: 0
        }
    },
    props: {
        userLibraryTime: { default: false },
    },

    computed: {
        //видимость карточки
        setVisible: {
           get() {
               this.visible = false;
           },
           set(value){
               this.visible = value;
           }
        },

        overallMinutesAnimated: function() {
            return `${this.overallMinutesTweened.toFixed(0)}${this.userLibraryTime.overallMinutesWord}`;
        },
        overallHoursAnimated: function(){
             return `${this.overallHoursTweened.toFixed(0)}${this.userLibraryTime.overallHoursWord}`;
        },
        overallDaysAnimated: function(){
             return `${this.overallDaysTweened.toFixed(0)}${this.userLibraryTime.overallDaysWord}`;
        },
        overallMonthsAnimated: function(){
             return `${this.overallMonthsTweened.toFixed(0)}${this.userLibraryTime.overallMonthsWord}`;
        }
    },

    watch: {
        overallMinutes: function([newValue, dur]) {
            gsap.to(this.$data, { duration: dur, overallMinutesTweened: newValue });
        },
        overallHours: function([newValue, dur]) {
            gsap.to(this.$data, { duration: dur, overallHoursTweened: newValue });
        },
        overallDays: function([newValue, dur]) {
            gsap.to(this.$data, { duration: dur, overallDaysTweened: newValue });
        },
        overallMonths: function([newValue, dur]) {
            gsap.to(this.$data, { duration: dur, overallMonthsTweened: newValue });
        }
    },

    //методы
    methods: {
        //при скролле страницы показать карточку когда она будет 
        //в поле видимости
        handleScroll: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setVisible = true;
                this.overallMinutes = [this.userLibraryTime.overallMinutes, this.userLibraryTime.overallMinutes / 1300];
                this.overallHours = [this.userLibraryTime.overallHours, this.userLibraryTime.overallMinutes / 1300];
                this.overallDays = [this.userLibraryTime.overallDays, this.userLibraryTime.overallMinutes / 1300];
                this.overallMonths = [this.userLibraryTime.overallMonths, this.userLibraryTime.overallMinutes / 1300];
            }
            return el.getBoundingClientRect().top < 700;   
        }
    }
}
</script>