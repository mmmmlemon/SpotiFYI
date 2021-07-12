//BackgroundImage
<template>
    <div :class="bgStyle" :style="{backgroundImage: `url('${backgroundImageUrl}')`}">
    </div>
</template>
<script>
export default {
    props: {
        backgroundImageUrl: { default: -1 },
        bgStyle: { default: 'backgroundImage invisible fadeInAnimBg' },
    }
}
</script>