<template>
    <div>
        <div class="top10_image_card" :style="{backgroundImage: `url('${item['cover']}')`}">
        </div>
        <div class="row fade_in_anim">
            <div class="col-2">
                <a href="item.url" target="_blank">
                    <img :src="item['cover']" class="rounded-circle album_icon_big">
                </a>
            </div>
            <div class="col-10">
                <h5 class="font_white margin_none">
                    <b>{{item['artists']}}</b>
                </h5>
                <p class=" font_white margin_none">
                    <a :href="item['trackUrl']" target="_blank">
                        <b>{{item['trackName']}}</b>
                    </a>
                </p>
                <p class="margin_none font_white" style="margin-bottom:7px;">
                    <a :href="item['albumUrl']" target="_blank">
                        <b class="unbold">{{item['albumName']}}</b>
                    </a>
                </p>
                <b>{{item['duration']}}</b>
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