//BackgroundImage
<template>
    <div class="backgroundImage" :style="{backgroundImage: `url('${backgroundImageUrl}')`}">
    </div>
</template>
<script>
export default {
    props: {
        backgroundImageUrl: { default: -1 },
    }
}
</script>