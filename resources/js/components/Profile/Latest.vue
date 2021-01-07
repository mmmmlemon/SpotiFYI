<template>
    <div class="row justify-content-center">
        <div class="col-md-8 fade_in_slow_anim" v-if="latestTracks != -1 && latestTracks != false">
            <h4 class="text-center">
                <b>Последние прослушанные треки</b>&nbsp;
                <i class="fas fa-compact-disc primary_color"></i>
            </h4>
        </div>
        <div class="col-md-6 col-10">
            <div>
                <List :items="latestTracks"/>
            </div>
        </div>
    </div>
 
</template>
<script>
export default {
    mounted(){

        //загружаем последние треки
        if(this.latestTracks == -1)
        { this.$store.dispatch('getLatestTracks'); }
    },

    computed: {
        //последние треки
        latestTracks: function(){
            return this.$store.state.profilePage.latestTracks;
        }
    }
}
</script>