//BackgroundImage
<template>
    <div class="background_image" :style="{backgroundImage: `url('${backgroundImageUrl}')`}">
    </div>
</template>
<script>
export default {
    props: {
        backgroundImageUrl: { default: -1 },
    }
}
</script>