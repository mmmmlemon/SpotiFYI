<template>
    <div>
        <h4>achievements</h4>
    </div>
</template>

<script>
export default {
    mounted(){
        //смена текущего таба
        this.$store.dispatch('setCurrentTab','achievements');
    }
}
</script>