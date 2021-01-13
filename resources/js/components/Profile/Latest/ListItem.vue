//ListItem
<template>
    <div>
        <!-- фоновое изображение -->
        <BackgroundImage :backgroundImageUrl="item['cover']"/>
        <div class="row fadeInAnim">
            <!-- обложка альбома -->
            <div class="col-2">
                <a :href="item['trackUrl']" target="_blank">
                    <img :src="item['cover']" class="rounded-circle albumIconBig">
                </a>
            </div>
            <div class="col-10">
                <!-- артист(ы) -->
                <h5 class="whiteColor marginNone">
                    <b>{{item['artists']}}</b>
                </h5>
                <!-- название трека -->
                <p class="whiteColor marginNone">
                    <a :href="item['trackUrl']" target="_blank">
                        <b>{{item['trackName']}}</b>
                    </a>
                </p>
                <hr>
                <!-- название альбома -->
                <p class="whiteColor marginNone">
                    <a :href="item['albumUrl']" target="_blank">
                        <b class="unbold">{{item['albumName']}}</b>
                    </a>
                </p>
                <!-- длительность -->
                <b class="font13pt">{{item['duration']}}</b>
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