//RecentTracks
<template>
    <div class="row justify-content-center">
        <div class="col-12 fadeInAnimSlow" v-if="recentTracks != -1 && recentTracks != false">
            <!-- заголовок -->
            <h4 class="text-center">
                <b>Последние прослушанные треки</b>&nbsp;
                <i class="fas fa-compact-disc primaryColor"></i>
            </h4>
        </div>
        <!-- вывод списка треков -->
        <div class="col-12 col-md-10 col-lg-6">
            <div>
                <List :items="recentTracks"/>
            </div>
        </div>
    </div>
 
</template>
<script>
export default {
    mounted(){
        //загружаем последние треки
        if(this.recentTracks == -1)
        { this.$store.dispatch('getLatestTracks'); }
    },

    computed: {
        //последние треки
        recentTracks: function(){
            return this.$store.state.homePage.recentTracks;
        }
    }
}
</script>