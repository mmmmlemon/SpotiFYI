<template>
    <div>
        <h1>Latest</h1>
    </div>
</template>
<script>
export default {
    mounted(){
        this.$store.dispatch('setCurrentTab', 'latest');
    },
}
</script>