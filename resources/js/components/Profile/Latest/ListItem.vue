//ListItem
<template>
    <div class="col-12">
        <div class="row justify-content-center fadeInAnim">
            <!-- обложка альбома -->
            <div class="col-8">
                <div class="row jusitify-content-center">
                    <div class="col-2 text-center">
                        <a :href="item['trackUrl']" target="_blank">
                            <img :src="item['cover']" class="rounded-circle top10Cover" style="width: 100%; transform: scale(1.2)">
                        </a>
                    </div>
                    <div class="col-10 text-right">
                        <!-- артист(ы) - название трека -->
                        <h5 class="textShadow">
                            <b>{{item['artists']}} - {{item['trackName']}}</b>
                        </h5>
                        <!-- название альбома -->
                        <p>
                            <a :href="item['albumUrl']" target="_blank">
                                {{item['albumName']}}
                            </a>
                        </p>
                        <!-- длительность -->
                        <h6 class=""><b>{{item['duration']}}</b></h6>
                    </div>
              
                </div>
                   <hr>  
            </div>
      
        </div>
    </div>
</template>
<script>
export default {
    props: {
        item: { default: -1 },
    }
}
</script>