<template>
    <div class="row justify-content-center">
        <div class="col-12" v-if="spotifyUserLibrary == -1">
            <Loader />
            <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
            <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == true">Анализирую треки...</h6>
            <p class="font_10pt text-center">Это может занять около минуты</p>
        </div>
        <div v-else-if="spotifyUserLibrary != -1" class="col-12">
            <div class="col-md-12 fade_in_slow_anim">
                <h5 class="text-center">
                    <b>Особо отличившиеся</b>&nbsp;
                    <i class="fas fa-award primary_color"></i>
                </h5>
            </div>
            <!-- навигация -->
            <div class="row justify-content-center font_10pt fade_in_anim">
                <nav class="justify-content-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#tracks">Треки</a></li>
                        <li class="breadcrumb-item"><a href="#artists">Исполнители</a></li>
                    </ul>
                </nav>
            </div>
            <!-- треки -->
            <div class="col-12 justify-content-center fade_in_anim" id="tracks">
                <h3 class="text-center">
                    Треки
                    <i class="fas fa-compact-disc primary_color"></i>
                </h3>
            </div>

            <div class="row justify-content-center">
                <AchievementItem cardTitle="Самый прослушиваемый трек" cardSubtitle="За всё время" :items="mostListenedTrack"/>
                <AchievementItem cardTitle="Самый прослушиваемый трек" cardSubtitle="За месяц" :items="mostListenedTrackMonth"/>

            </div>


        </div>
        <div v-else-if="spotifyUserLibrary == false">
            <Error errorMessage="Не удалось загрузить библиотеку пользователя"/>
        </div>
        <div v-else>
            <Error errorMessage="Неизвестная ошибка"/>
        </div>
    </div>
</template>

<script>
export default {
    mounted(){
        //смена текущего таба
        this.$store.dispatch('setCurrentTab','achievements');

        //получить библиотеку пользователя, если нужно
        if(this.spotifyUserLibrary == -1)
        { this.$store.dispatch('getSpotifyUserLibrary'); }

        //самый прослушиваемый трек
        if(this.mostListenedTrack == -1)
        { this.$store.dispatch('getMostListenedTrack') };

        //самый прослушиваемый трек за месяц
        if(this.mostListenedTrackMonth == -1)
        { this.$store.dispatch('getMostListenedTrackMonth') };

    },
    computed: {
        spotifyUserLibrary: function(){
            return this.$store.state.profilePage.spotifyUserLibrary;
            // return true;
        },
        mostListenedTrack: function(){
            return this.$store.state.profilePage.mostListenedTrack;
        },
        mostListenedTrackMonth: function(){
            return this.$store.state.profilePage.mostListenedTrackMonth;
        }
    }
}
</script>