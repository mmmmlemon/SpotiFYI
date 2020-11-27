<template>
      <div class="container profile_bg" :style="{backgroundImage: `url('${profileBackgroundUrl}')`}">
      </div>
</template>

<script>
export default {
    props:{
          profileBackgroundUrl: { default: -1 },
    }
}
</script>