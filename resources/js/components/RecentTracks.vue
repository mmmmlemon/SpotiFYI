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

    created(){
        this.checkToken ().then(response => {
            if(response === 'refresh'){
                var url = window.location.href;
                var indexOfAnchor = url.indexOf('#');
                if(indexOfAnchor != -1)
                {var url = url.slice(0, indexOfAnchor);}
                axios.get('/refresh_token').then(response => {
                    if(response.data = true){
                        
                        window.location.replace(url);
                    }
                })

            } else {             
                //загружаем последние треки
                if(this.recentTracks == -1)
                { this.$store.dispatch('getLatestTracks'); }
            }
        });

    },

    computed: {
        //последние треки
        recentTracks: function(){
            return this.$store.state.homePage.recentTracks;
        }
    }
}
</script>